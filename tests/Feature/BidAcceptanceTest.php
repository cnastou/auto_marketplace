<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\TransportRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BidAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_accept_a_bid(): void
    {
        $owner = User::factory()->create();
        $bidderOne = User::factory()->create();
        $bidderTwo = User::factory()->create();

        $transportRequest = TransportRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => 'pending',
        ]);

        $bidOne = Bid::factory()->create([
            'transport_request_id' => $transportRequest->id,
            'user_id' => $bidderOne->id,
            'amount' => 1250,
            'status' => 'pending',
        ]);

        $bidTwo = Bid::factory()->create([
            'transport_request_id' => $transportRequest->id,
            'user_id' => $bidderTwo->id,
            'amount' => 1100,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($owner)
            ->patch(route('bids.accept', $bidTwo));

        $response->assertRedirect(
            route('transport-requests.show', $transportRequest, absolute: false)
        );

        $this->assertDatabaseHas('bids', [
            'id' => $bidTwo->id,
            'status' => 'accepted',
        ]);

        $this->assertDatabaseHas('bids', [
            'id' => $bidOne->id,
            'status' => 'rejected',
        ]);

        $this->assertDatabaseHas('transport_requests', [
            'id' => $transportRequest->id,
            'status' => 'accepted',
        ]);
    }

    public function test_owner_sees_accept_bid_action_on_transport_request_page(): void
    {
        $owner = User::factory()->create();
        $bidder = User::factory()->create();

        $transportRequest = TransportRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => 'pending',
        ]);

        $bid = Bid::factory()->create([
            'transport_request_id' => $transportRequest->id,
            'user_id' => $bidder->id,
            'amount' => 1400,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($owner)
            ->get(route('transport-requests.show', $transportRequest));

        $response->assertOk();
        $response->assertSee(route('bids.accept', $bid), false);
        $response->assertSee('Accept Bid', false);
    }

    public function test_non_owner_cannot_accept_a_bid(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $bidder = User::factory()->create();

        $transportRequest = TransportRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => 'pending',
        ]);

        $bid = Bid::factory()->create([
            'transport_request_id' => $transportRequest->id,
            'user_id' => $bidder->id,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->patch(route('bids.accept', $bid));

        $response->assertForbidden();

        $this->assertDatabaseHas('bids', [
            'id' => $bid->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('transport_requests', [
            'id' => $transportRequest->id,
            'status' => 'pending',
        ]);
    }
}
