<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Available Transport Jobs | Auto Marketplace</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f4f7fb;
        }

        .jobs-hero {
            background: linear-gradient(120deg, #0b2e59 0%, #0d6efd 100%);
        }

        .job-card {
            transition:
                transform 180ms ease,
                box-shadow 180ms ease;
        }

        .job-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 1rem 2rem rgba(33, 37, 41, 0.12) !important;
        }

        .job-location {
            overflow-wrap: anywhere;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">Auto Marketplace</a>

            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-light btn-sm">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Create account</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-lg-5 container py-4">
        <section class="jobs-hero rounded-4 p-md-5 mb-4 p-4 text-white shadow-sm">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge rounded-pill text-bg-light text-primary mb-3">Open opportunities</span>
                    <h1 class="display-6 fw-bold mb-3">Available transport jobs</h1>
                    <p class="lead mb-0 text-white">
                        Explore vehicle moves, compare routes and schedules, and submit a bid that works for you.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-inline-flex align-items-center rounded-3 text-dark gap-2 bg-white px-3 py-2 shadow-sm">
                        <span class="fs-4 fw-bold text-primary">{{ number_format($requests->count()) }}</span>
                        <span class="small text-secondary text-start">
                            {{ \Illuminate\Support\Str::plural('job', $requests->count()) }}<br />currently open
                        </span>
                    </div>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end mb-3 gap-2">
            <div>
                <h2 class="h4 fw-bold text-dark mb-1">Browse open requests</h2>
                <p class="text-secondary mb-0">Review pickup and delivery details before you bid.</p>
            </div>
            <span class="small text-secondary">Sorted by most recently posted</span>
        </div>

        @forelse ($requests as $request)
            <article class="job-card card mb-3 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-9">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-start mb-4 gap-2">
                                <div>
                                    <span class="badge text-bg-success-subtle text-success-emphasis mb-2">Open for bids</span>
                                    <h3 class="h4 fw-bold text-dark mb-1">
                                        {{ $request->vehicle->year }} {{ $request->vehicle->make }} {{ $request->vehicle->model }}
                                    </h3>
                                    <p class="small text-secondary mb-0">Request #{{ $request->id }}</p>
                                </div>
                                <p class="small text-secondary mb-0">
                                    Posted {{ $request->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="h-100 rounded-3 bg-light p-3">
                                        <p class="small fw-bold text-primary text-uppercase mb-2">Pickup</p>
                                        <p class="job-location fw-semibold text-dark mb-1">
                                            {{ $request->pickup_location }}
                                        </p>
                                        <p class="small text-secondary mb-0">
                                            {{ $request->pickup_date->format('F j, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="h-100 rounded-3 bg-light p-3">
                                        <p class="small fw-bold text-primary text-uppercase mb-2">Delivery</p>
                                        <p class="job-location fw-semibold text-dark mb-1">
                                            {{ $request->delivery_location }}
                                        </p>
                                        <p class="small text-secondary mb-0">
                                            {{ $request->delivery_date->format('F j, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <p class="small text-secondary mb-0 mt-3">
                                Posted by <span class="fw-semibold text-dark">{{ $request->user->name }}</span>
                            </p>
                        </div>

                        <div class="col-lg-3">
                            <a
                                href="{{ route('transport-requests.show', $request) }}"
                                class="btn btn-primary btn-lg w-100"
                            >
                                View job and bid
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <section class="card border-0 px-3 py-5 text-center shadow-sm">
                <div class="card-body">
                    <span class="badge rounded-pill text-bg-primary-subtle text-primary mb-3">All caught up</span>
                    <h2 class="h4 fw-bold text-dark">No open jobs right now</h2>
                    <p class="text-secondary mb-0">New transport requests will appear here when they are posted.</p>
                </div>
            </section>
        @endforelse

        <p class="text-secondary small mb-0 mt-4 text-center">
            Auto Marketplace <span aria-hidden="true">&middot;</span> Transport jobs
        </p>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
