@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .dashboard-page {
            min-height: 70vh;
            background: #f4f7fb;
        }

        .dashboard-hero {
            background: linear-gradient(120deg, #0b2e59 0%, #0d6efd 100%);
        }

        .dashboard-stat-card {
            transition:
                transform 180ms ease,
                box-shadow 180ms ease;
        }

        .dashboard-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 1rem 2rem rgba(33, 37, 41, 0.12) !important;
        }

        .dashboard-stat-value {
            font-size: clamp(2rem, 5vw, 2.75rem);
            line-height: 1.1;
            overflow-wrap: anywhere;
        }
    </style>
@endpush

<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div>
                <p class="small fw-bold text-primary text-uppercase mb-1">Auto Marketplace</p>
                <h1 class="h3 fw-bold text-dark mb-0">Dashboard</h1>
            </div>
            <p class="text-secondary mb-0">Your marketplace activity at a glance</p>
        </div>
    </x-slot>

    <main class="dashboard-page py-lg-5 py-4">
        <div class="container">
            <section class="dashboard-hero rounded-4 p-md-5 mb-lg-5 mb-4 p-4 text-white shadow-sm">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="badge rounded-pill text-bg-light text-primary mb-3">Marketplace overview</span>
                        <h2 class="display-6 fw-bold mb-3">Welcome back, {{ Auth::user()->name }}</h2>
                        <p class="lead mb-0 text-white">
                            Track transport requests and bidding activity, and see what needs attention.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('transport-requests.index') }}" class="btn btn-light btn-lg fw-semibold">
                            Browse transport jobs
                        </a>
                    </div>
                </div>
            </section>

            @php
                $cards = [
                    [
                        'label' => 'Transport requests',
                        'value' => $stats['transport_requests'],
                        'description' => 'Requests listed across the marketplace',
                        'tone' => 'primary',
                    ],
                    [
                        'label' => 'Total bids',
                        'value' => $stats['bids'],
                        'description' => 'Offers submitted by carriers',
                        'tone' => 'success',
                    ],
                    [
                        'label' => 'Pending bids',
                        'value' => $stats['pending_bids'],
                        'description' => 'Offers waiting for a decision',
                        'tone' => 'warning',
                    ],
                ];
            @endphp

            <section aria-labelledby="activity-heading">
                <div class="d-flex flex-column flex-sm-row align-items-sm-end justify-content-between mb-3 gap-2">
                    <div>
                        <h2 id="activity-heading" class="h4 fw-bold text-dark mb-1">Marketplace activity</h2>
                        <p class="text-secondary mb-0">Current totals from across the platform</p>
                    </div>
                    <span class="small text-secondary">Updated {{ now()->format('M j, Y') }}</span>
                </div>

                <div class="row g-3 g-lg-4">
                    @foreach ($cards as $card)
                        <div class="col-12 col-md-6 col-xl-4">
                            <article class="dashboard-stat-card card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start justify-content-between gap-3">
                                        <div>
                                            <h3 class="h6 fw-semibold text-secondary mb-3">{{ $card['label'] }}</h3>
                                            <p class="dashboard-stat-value fw-bold text-dark mb-3">
                                                {{ number_format($card['value']) }}
                                            </p>
                                        </div>
                                        <span
                                            class="badge text-bg-{{ $card['tone'] }} rounded-3 fs-5 p-3"
                                            aria-hidden="true"
                                        >
                                            @if ($card['tone'] === 'primary')
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 7h11v10H3zM14 10h4l3 3v4h-7z" />
                                                    <circle cx="7.5" cy="18" r="1.5" />
                                                    <circle cx="17.5" cy="18" r="1.5" />
                                                </svg>
                                            @elseif ($card['tone'] === 'success')
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="9" />
                                                    <path d="M16 8.5c-.8-.7-2-1-3.5-1-1.7 0-3 .8-3 2s1.3 2 3 2 3 .8 3 2-1.3 2-3 2c-1.5 0-2.7-.4-3.5-1M12.5 6v12" />
                                                </svg>
                                            @else
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="9" />
                                                    <path d="M12 7v5l3 2" />
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <p class="border-top text-secondary small mb-0 pt-3">{{ $card['description'] }}</p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>

            <p class="text-secondary small mb-0 mt-4 text-center">
                Auto Marketplace <span aria-hidden="true">&middot;</span> Dashboard
            </p>
        </div>
    </main>
</x-app-layout>
