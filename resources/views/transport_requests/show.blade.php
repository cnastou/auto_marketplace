<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Transport Request #{{ $transportRequest->id }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}"> Auto Marketplace </a>

            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('transport-requests.index') }}" class="btn btn-outline-light"> Browse Jobs </a>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf

                        <button type="submit" class="btn btn-light">Logout</button>
                    </form>

                @else
                    <a href="{{ route('login') }}" class="btn btn-light"> Login </a>

                    <a href="{{ route('register') }}" class="btn btn-primary"> Register </a>

                @endauth
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="mb-4">
            <a href="{{ route('transport-requests.index') }}" class="text-decoration-none">
                &larr; Back to Available Jobs
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Vehicle Information -->

        <div class="mb-4">
            <h1 class="fw-bold">
                {{ $transportRequest->vehicle->year }} {{ $transportRequest->vehicle->make }} {{ $transportRequest->vehicle->model }}
            </h1>

            <p class="text-muted mb-0">Transport Request #{{ $transportRequest->id }}</p>
        </div>

        <!-- Pickup / Delivery -->

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">Pickup</h5>

                        <p class="mb-1">{{ $transportRequest->pickup_location }}</p>

                        <p class="text-muted mb-0">{{ $transportRequest->pickup_date->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">Delivery</h5>

                        <p class="mb-1">{{ $transportRequest->delivery_location }}</p>

                        <p class="text-muted mb-0">{{ $transportRequest->delivery_date->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer -->

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold">Customer</h5>

                <p class="mb-0">{{ $transportRequest->user->name }}</p>
            </div>
        </div>

        <!-- Notes -->

        @if ($transportRequest->notes)
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Notes</h5>

                    <p class="mb-0">{{ $transportRequest->notes }}</p>
                </div>
            </div>

        @endif

        <!-- Bid Form -->
        <!-- Bid Form -->

        @if (auth()->check() && auth()->id() === $transportRequest->user_id)
            <div class="alert alert-info">
                This is your transport request. Review the bids below to choose a provider.
            </div>

        @elseif ($transportRequest->status === 'pending')
            @auth
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Submit Your Bid</h4>

                        <form action="{{ route('bids.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="transport_request_id" value="{{ $transportRequest->id }}" />

                            <div class="mb-3">
                                <label for="amount" class="form-label fw-semibold"> Bid Amount </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="1"
                                    class="form-control"
                                    id="amount"
                                    name="amount"
                                    value="{{ old('amount') }}"
                                    placeholder="Enter your bid amount"
                                    required
                                />
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label fw-semibold"> Notes </label>

                                <textarea
                                    class="form-control"
                                    id="notes"
                                    name="notes"
                                    rows="4"
                                    placeholder="Add any notes about your bid..."
                                >{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit Bid</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <strong>Want to bid on this job?</strong>
                    <p class="mb-0 mt-2">
                        You must have an account and be logged in to submit a bid.

                        <a href="{{ route('register') }}">Create an account</a>
                        or
                        <a href="{{ route('login') }}">Log in</a>.
                    </p>
                </div>

            @endauth

        @else
            <div class="alert alert-warning">This transport request is no longer accepting bids.</div>

        @endif

        <!-- Bids -->

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3">Bids Received</h4>

                @forelse ($transportRequest->bids as $bid)
                    <div class="mb-3 rounded border p-3">
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <div>
                                <h5 class="mb-1">{{ $bid->user->name }}</h5>

                                <div class="text-muted">Bid amount: ${{ number_format($bid->amount, 2) }}</div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                @if (auth()->check() && auth()->id() === $transportRequest->user_id && $bid->status === 'pending')
                                    <form action="{{ route('bids.accept', $bid) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn btn-success btn-sm">Accept Bid</button>
                                    </form>
                                @endif

                                <span class="badge bg-secondary"> {{ ucfirst($bid->status) }} </span>
                            </div>
                        </div>

                        @if ($bid->notes)
                            <p class="mb-0 mt-3">{{ $bid->notes }}</p>

                        @endif
                    </div>

                @empty
                    <p class="text-muted mb-0">No bids have been submitted yet.</p>

                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
