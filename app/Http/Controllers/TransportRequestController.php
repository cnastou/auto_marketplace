<?php

namespace App\Http\Controllers;

use App\Models\TransportRequest;
use Illuminate\View\View;

class TransportRequestController extends Controller
{
    public function index(): View
    {
        $requests = TransportRequest::with(['user', 'vehicle'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('transport_requests.index', compact('requests'));
    }

    public function show(TransportRequest $transportRequest): View
    {
        $transportRequest->load(['user', 'vehicle', 'bids.user']);

        return view('transport_requests.show', compact('transportRequest'));
    }
}