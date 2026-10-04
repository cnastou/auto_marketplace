<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Available Transport Jobs</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand fw-bold"
           href="{{ route('home') }}">
            Auto Marketplace
        </a>

        <div class="d-flex align-items-center gap-2">

            @auth

                <a href="{{ route('transport-requests.index') }}"
                   class="btn btn-outline-light">
                    Browse Jobs
                </a>

                <form action="{{ route('logout') }}"
                      method="POST"
                      class="d-inline">
                    @csrf

                    <button type="submit"
                            class="btn btn-light">
                        Logout
                    </button>
                </form>

            @else

                <a href="{{ route('login') }}"
                   class="btn btn-light">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="btn btn-primary">
                    Register
                </a>

            @endauth

        </div>
    </div>
</nav>


<div class="container py-5">

    <div class="mb-5">
        <h1 class="fw-bold">
            Available Transport Jobs
        </h1>

        <p class="text-muted">
            Browse available vehicle transport requests and submit your bid.
        </p>
    </div>


    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    @forelse ($requests as $request)

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body p-4">

                <div class="row">

                    <div class="col-md-8">

                        <h3 class="fw-bold">
                            {{ $request->vehicle->year }}
                            {{ $request->vehicle->make }}
                            {{ $request->vehicle->model }}
                        </h3>

                        <p class="text-muted mb-3">
                            Transport Request #{{ $request->id }}
                        </p>


                        <div class="row">

                            <div class="col-md-6">

                                <h6 class="fw-bold">
                                    Pickup
                                </h6>

                                <p class="mb-1">
                                    {{ $request->pickup_location }}
                                </p>

                                <p class="text-muted">
                                    {{ $request->pickup_date->format('F j, Y') }}
                                </p>

                            </div>


                            <div class="col-md-6">

                                <h6 class="fw-bold">
                                    Delivery
                                </h6>

                                <p class="mb-1">
                                    {{ $request->delivery_location }}
                                </p>

                                <p class="text-muted">
                                    {{ $request->delivery_date->format('F j, Y') }}
                                </p>

                            </div>

                        </div>


                        <p class="mb-0">
                            <strong>Customer:</strong>
                            {{ $request->user->name }}
                        </p>

                    </div>


                    <div class="col-md-4 d-flex align-items-center">

                        <a href="{{ route('transport-requests.show', $request) }}"
                           class="btn btn-primary w-100">
                            View Job & Submit Bid
                        </a>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="alert alert-info">
            There are currently no available transport jobs.
        </div>

    @endforelse

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>