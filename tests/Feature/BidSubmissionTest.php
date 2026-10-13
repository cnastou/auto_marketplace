<?php

namespace Tests\Feature;

use App\Models\TransportRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BidSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_a_bid(): void
    {
        $user = User::factory()->create();

        $transportRequest = TransportRequest::factory()->create();

        $response = $this->actingAs($user)->post(route('bids.store'), [
            'transport_request_id' => $transportRequest->id,
            'amount' => 1250,
            'notes' => 'Good price for this transport.',
        ]);

        $response->assertRedirect(
            route('transport-requests.show', $transportRequest, absolute: false)
        );

        $this->assertDatabaseHas('bids', [
            'transport_request_id' => $transportRequest->id,
            'user_id' => $user->id,
            'amount' => 1250,
            'status' => 'pending',
            'notes' => 'Good price for this transport.',
        ]);
    }

    public function test_guest_cannot_submit_a_bid(): void
    {
        $transportRequest = TransportRequest::factory()->create();

        $response = $this->post(route('bids.store'), [
            'transport_request_id' => $transportRequest->id,
            'amount' => 1250,
            'notes' => 'Good price for this transport.',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('bids', 0);
    }

    public function test_bid_amount_must_be_at_least_one(): void
    {
        $user = User::factory()->create();

        $transportRequest = TransportRequest::factory()->create();

        $response = $this->actingAs($user)->post(route('bids.store'), [
            'transport_request_id' => $transportRequest->id,
            'amount' => 0,
            'notes' => 'Invalid bid.',
        ]);

        $response->assertSessionHasErrors('amount');

        $this->assertDatabaseCount('bids', 0);
    }

    public function test_submitting_a_bid_invalidates_dashboard_stats(): void
    {
        // Use an in-memory cache instead of real Redis during this test.
        /*config()->set('cache.stores.redis', [
            'driver' => 'array',
        ]);

        Cache::forgetDriver('redis');*/

        $user = User::factory()->create();

        $transportRequest = TransportRequest::factory()->create();

        Cache::store('redis')->put('marketplace:dashboard:stats', 'invalid_data');

        $response = $this->actingAs($user)->post(route('bids.store'), [
            'transport_request_id' => $transportRequest->id,
            'amount' => 1250,
            'notes' => 'Good price for this transport.',
        ]);

        $response->assertRedirect(
            route('transport-requests.show', $transportRequest, absolute: false)
        );

        $this->assertDatabaseHas('bids', [
            'transport_request_id' => $transportRequest->id,
            'user_id' => $user->id,
            'amount' => 1250,
            'status' => 'pending',
            'notes' => 'Good price for this transport.',
        ]);

        // Ensure the invalid cache is cleared after bid submission
        $this->assertNull(Cache::store('redis')->get('marketplace:dashboard:stats'));
    }
}
