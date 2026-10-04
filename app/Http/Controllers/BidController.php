<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\Http\Request;
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

        $validated['user_id'] = auth()->id();
        // $validated['user_id'] = 1;
        $validated['status'] = 'pending';

        Bid::create($validated);

        return redirect()
            ->route('transport-requests.show', $validated['transport_request_id'])
            ->with('success', 'Your bid has been submitted.');
    }

    public function accept(Bid $bid)
    {
        if ($bid->transportRequest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::transaction(function () use ($bid) {
            $bid->update([
                'status' => 'accepted',
            ]);

            Bid::where('transport_request_id', $bid->transport_request_id)
                ->where('id', '!=', $bid->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                ]);

            $bid->transportRequest->update([
                'status' => 'accepted',
            ]);
        });

        return redirect()
            ->route('transport-requests.show', $bid->transport_request_id)
            ->with('success', 'The bid has been accepted.');
    }
}
