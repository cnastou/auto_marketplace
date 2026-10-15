<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\TransportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function index()
    {
        $bids = Bid::all();

        return view('bid.index', compact('bids'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transport_request_id' => 'required|exists:transport_requests,id',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
        ]);

        $transportRequest = TransportRequest::findOrFail($validated['transport_request_id']);
        if ($transportRequest->user_id === auth()->id()) {
            abort(403, 'You cannot bid on your own transport request.');
        }

        if ($transportRequest->status !== 'pending') {
            abort(403, 'You cannot bid on a transport request that is not pending.');
        }

        $validated['user_id'] = auth()->id();
        // $validated['user_id'] = 1;
        $validated['status'] = 'pending';

        Bid::create($validated);
        Cache::store('redis')->forget('marketplace:dashboard:stats');

        return redirect()
            ->route('transport-requests.show', $validated['transport_request_id'])
            ->with('success', 'Your bid has been submitted.');
    }

    public function accept(Bid $bid): RedirectResponse
    {
        DB::transaction(function () use ($bid): void {
            $transportRequest = TransportRequest::query()
                ->lockForUpdate()
                ->findOrFail($bid->transport_request_id);

            if ($transportRequest->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            $lockedBid = $transportRequest->bids()
                ->lockForUpdate()
                ->findOrFail($bid->id);

            if ($transportRequest->status !== 'pending' || $lockedBid->status !== 'pending') {
                abort(409, 'Only pending bids on pending transport requests can be accepted.');
            }

            $lockedBid->update([
                'status' => 'accepted',
            ]);

            $transportRequest->bids()
                ->where('id', '!=', $lockedBid->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                ]);

            $transportRequest->update([
                'status' => 'accepted',
            ]);
        });

        Cache::store('redis')->forget('marketplace:dashboard:stats');

        return redirect()
            ->route('transport-requests.show', $bid->transport_request_id)
            ->with('success', 'The bid has been accepted.');
    }
}
