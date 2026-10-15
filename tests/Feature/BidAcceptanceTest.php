<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\TransportRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\TestWith;
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

        Cache::store('redis')->put('marketplace:dashboard:stats', 'cached statistics');

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

        $this->assertNull(Cache::store('redis')->get('marketplace:dashboard:stats'));
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

    #[TestWith(['accepted', 'pending'], 'accepted bid')]
    #[TestWith(['rejected', 'pending'], 'rejected bid')]
    #[TestWith(['withdrawn', 'pending'], 'withdrawn bid')]
    #[TestWith(['pending', 'accepted'], 'accepted request')]
    #[TestWith(['pending', 'in_transit'], 'in-transit request')]
    #[TestWith(['pending', 'completed'], 'completed request')]
    #[TestWith(['pending', 'cancelled'], 'cancelled request')]
    public function test_acceptance_returns_conflict_without_changes_when_bid_or_request_is_not_pending(
        string $bidStatus,
        string $requestStatus
    ): void {
        $owner = User::factory()->create();
        $transportRequest = TransportRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => $requestStatus,
        ]);
        $bid = Bid::factory()->create([
            'transport_request_id' => $transportRequest->id,
            'status' => $bidStatus,
        ]);
        Bid::factory()->create([
            'transport_request_id' => $transportRequest->id,
            'status' => 'pending',
        ]);

        $originalRequest = $transportRequest->fresh()->getAttributes();
        $originalBids = Bid::orderBy('id')->get()->toArray();
        $cachedStats = 'cached statistics';
        Cache::store('redis')->put('marketplace:dashboard:stats', $cachedStats);
        $this->travel(1)->seconds();

        $response = $this->actingAs($owner)->patch(route('bids.accept', $bid));

        $response->assertConflict();

        $this->assertSame($originalRequest, $transportRequest->fresh()->getAttributes());
        $this->assertSame($originalBids, Bid::orderBy('id')->get()->toArray());

        $this->assertSame($cachedStats, Cache::store('redis')->get('marketplace:dashboard:stats'));
    }

    #[TestWith([0], 'same bid')]
    #[TestWith([1], 'rejected competing bid')]
    public function test_repeated_acceptance_returns_conflict_and_preserves_the_original_winner(
        int $retryBidIndex
    ): void {
        $owner = User::factory()->create();
        $transportRequest = TransportRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => 'pending',
        ]);
        $bids = Bid::factory()->count(2)->create([
            'transport_request_id' => $transportRequest->id,
            'status' => 'pending',
        ]);

        $this->actingAs($owner)
            ->patch(route('bids.accept', $bids[0]))
            ->assertRedirect(route('transport-requests.show', $transportRequest, absolute: false));

        $this->assertDatabaseHas('transport_requests', [
            'id' => $transportRequest->id,
            'status' => 'accepted',
        ]);
        $this->assertSame(['accepted', 'rejected'], $transportRequest->bids()->orderBy('id')->pluck('status')->all());

        $originalRequest = $transportRequest->fresh()->getAttributes();
        $originalBids = Bid::orderBy('id')->get()->toArray();
        $cachedStats = 'refreshed statistics';
        Cache::store('redis')->put('marketplace:dashboard:stats', $cachedStats);
        $this->travel(1)->seconds();

        $response = $this->patch(route('bids.accept', $bids[$retryBidIndex]));

        $response->assertConflict();

        $this->assertSame($originalRequest, $transportRequest->fresh()->getAttributes());
        $this->assertSame($originalBids, Bid::orderBy('id')->get()->toArray());

        $this->assertSame($cachedStats, Cache::store('redis')->get('marketplace:dashboard:stats'));
    }

    public function test_non_owner_receives_forbidden_for_a_closed_request_without_changes(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $transportRequest = TransportRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => 'accepted',
        ]);
        $bid = Bid::factory()->create([
            'transport_request_id' => $transportRequest->id,
            'status' => 'pending',
        ]);

        $originalRequest = $transportRequest->fresh()->getAttributes();
        $originalBid = $bid->fresh()->getAttributes();
        $cachedStats = 'cached statistics';
        Cache::store('redis')->put('marketplace:dashboard:stats', $cachedStats);
        $this->travel(1)->seconds();

        $response = $this->actingAs($otherUser)->patch(route('bids.accept', $bid));

        $response->assertForbidden();

        $this->assertSame($originalRequest, $transportRequest->fresh()->getAttributes());
        $this->assertSame($originalBid, $bid->fresh()->getAttributes());

        $this->assertSame($cachedStats, Cache::store('redis')->get('marketplace:dashboard:stats'));
    }
}
