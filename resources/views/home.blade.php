<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Auto Marketplace</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f8f9fa;
        }

        .hero {
            background: linear-gradient(135deg, #0d6efd, #084298);
            color: white;
            padding: 100px 0;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
        }

        .feature-card {
            transition: transform 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand fw-bold" href="/">
            Auto Marketplace
        </a>

        <div class="d-flex gap-2">

            @auth

                <a
                    href="{{ route('transport-requests.index') }}"
                    class="btn btn-outline-light"
                >
                    Browse Jobs
                </a>

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="d-inline"
                >
                    @csrf

                    <button type="submit" class="btn btn-light">
                        Logout
                    </button>
                </form>

            @else

                <a
                    href="{{ route('login') }}"
                    class="btn btn-light"
                >
                    Login
                </a>

                <a
                    href="{{ route('register') }}"
                    class="btn btn-primary"
                >
                    Register
                </a>

            @endauth

        </div>

    </div>

</nav>


<section class="hero">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-7">

                <h1 class="mb-4">
                    Move Your Vehicle With Confidence
                </h1>

                <p class="lead mb-4">
                    Connect vehicle owners with transportation providers,
                    compare bids, and find the right solution for your vehicle.
                </p>

                <div class="d-flex gap-3">

                    @auth

                        <a
                            href="{{ route('transport-requests.index') }}"
                            class="btn btn-light btn-lg"
                        >
                            Browse Transport Jobs
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="btn btn-light btn-lg"
                        >
                            Login to Browse Jobs
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="btn btn-outline-light btn-lg"
                        >
                            Create an Account
                        </a>

                    @endauth

                </div>

            </div>

        </div>

    </div>

</section>


<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                How Auto Marketplace Works
            </h2>

            <p class="text-muted">
                A simple marketplace for vehicle transportation.
            </p>

        </div>


        <div class="row g-4">

            <div class="col-md-4">

                <div class="card feature-card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h4 class="fw-bold">
                            1. Post a Job
                        </h4>

                        <p class="text-muted">
                            Vehicle owners create a transport request with
                            pickup, delivery, vehicle, and scheduling details.
                        </p>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card feature-card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h4 class="fw-bold">
                            2. Receive Bids
                        </h4>

                        <p class="text-muted">
                            Transportation providers review available jobs
                            and submit competitive bids.
                        </p>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card feature-card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h4 class="fw-bold">
                            3. Choose a Provider
                        </h4>

                        <p class="text-muted">
                            Customers review the bids they receive and select
                            a provider for their vehicle.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<section class="py-5 bg-white">

    <div class="container text-center">

        <h2 class="fw-bold mb-3">
            Ready to Get Started?
        </h2>

        <p class="text-muted mb-4">
            Create an account and start using the marketplace.
        </p>

        @auth

            <a
                href="{{ route('transport-requests.index') }}"
                class="btn btn-primary btn-lg"
            >
                Browse Transport Jobs
            </a>

        @else

            <a
                href="{{ route('register') }}"
                class="btn btn-primary btn-lg"
            >
                Create Your Account
            </a>

        @endauth

    </div>

</section>


<footer class="bg-dark text-white py-4">

    <div class="container text-center">

        <p class="mb-0">
            &copy; {{ date('Y') }} Auto Marketplace
        </p>

    </div>

</footer>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>