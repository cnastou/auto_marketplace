# Auto Marketplace

A Laravel-based vehicle transportation marketplace where customers can create vehicle transport requests and transportation providers can submit bids for those jobs.

This project was built as a practical portfolio application to demonstrate modern Laravel development, relational database design, authentication, authorization, marketplace business rules, and automated testing.

## Overview

The application models a vehicle transportation marketplace:

1. A customer registers and manages their account.
2. A customer adds a vehicle and creates a transport request.
3. Transport providers can view available transport jobs.
4. Providers submit bids for transport requests.
5. The customer reviews the submitted bids.
6. The customer accepts a bid.
7. The accepted bid is selected and the remaining bids are rejected.
8. The transport request can progress through its lifecycle.

The project focuses on backend functionality, database relationships, authorization, and business logic while providing a simple web interface.

## Features

### Authentication

* User registration and login
* Logout
* Password confirmation
* Password reset
* Email verification
* Profile management
* Authentication and authorization

### Vehicle Management

Users can associate vehicles with their transport requests.

Vehicle information includes:

* Make
* Model
* Year
* VIN
* Pickup location
* Delivery location
* Status

VINs are uniquely constrained in the database.

### Transport Requests

Customers can create and view vehicle transportation requests.

Each request includes:

* Vehicle
* Pickup location
* Delivery location
* Pickup date
* Delivery date
* Status
* Notes

Transport request statuses include:

* `pending`
* `accepted`
* `in_transit`
* `completed`
* `cancelled`

### Bidding

Transportation providers can submit bids for available transport requests.

Each bid contains:

* Transport request
* User
* Amount
* Status
* Notes

Bid statuses include:

* `pending`
* `accepted`
* `rejected`

### Bid Acceptance

When the owner of a transport request accepts a bid:

* The selected bid is marked `accepted`.
* Other bids for the same request are marked `rejected`.
* Only the owner of the transport request can accept its bids.
* Authorization prevents unrelated users from modifying another customer's bids.

These rules are enforced by the application rather than relying solely on the frontend.

## Database Relationships

The main Eloquent relationships are:

```text
User
 ├── hasMany Vehicles
 ├── hasMany TransportRequests
 └── hasMany Bids

Vehicle
 └── belongsTo User

TransportRequest
 ├── belongsTo User
 ├── belongsTo Vehicle
 └── hasMany Bids

Bid
 ├── belongsTo User
 └── belongsTo TransportRequest
```

The database schema is managed through Laravel migrations and Eloquent relationships.

## Testing

The project uses Laravel's PHPUnit testing framework.

The test suite currently covers:

* Authentication
* User registration
* Profile management
* Password functionality
* Email verification
* Transport requests
* Bid submission
* Bid acceptance

### Bid Submission Test

Verifies that an authenticated user can submit a bid with:

* A valid transport request
* A valid bid amount
* Optional notes
* The authenticated user's ID
* A default `pending` status

### Bid Acceptance Test

Verifies that:

* The transport request owner can accept a bid.
* The selected bid becomes `accepted`.
* Other bids for the same transport request become `rejected`.
* Unauthorized users cannot accept another customer's bid.

Tests use Laravel's `RefreshDatabase` functionality to keep database state isolated between tests.

## Technology Stack

### Backend

* PHP 8.5
* Laravel 13
* Eloquent ORM
* PHPUnit

### Database

* MySQL
* Laravel Migrations
* Eloquent Relationships
* Database Factories

### Frontend

* Blade
* Bootstrap
* JavaScript
* CSS
* Vite

### Development Tools

* Composer
* npm
* Git
* Laravel Boost
* VS Code
* WSL

## Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   └── Requests/
├── Models/
├── Providers/
└── View/

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── css/
├── js/
└── views/

routes/
├── auth.php
├── console.php
└── web.php

tests/
├── Feature/
└── Unit/
```

## Installation

Clone the repository and install the PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

Configure the database connection in `.env`.

Run the database migrations:

```bash
php artisan migrate
```

## Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

In a separate terminal, run the frontend development server:

```bash
npm run dev
```

The application can then be accessed through the local Laravel development URL.

## Running Tests

Run the complete test suite:

```bash
php artisan test
```

Run a specific feature test:

```bash
php artisan test tests/Feature/BidAcceptanceTest.php
```

## Development Goals

This project is designed to demonstrate practical full-stack and backend development skills, including:

* Laravel MVC architecture
* Eloquent relationships
* Authentication and authorization
* Form validation
* Database migrations
* Database constraints
* Marketplace business logic
* Automated testing
* Factory-based test data
* Git/GitHub development workflow
* Clean separation between application logic and presentation

## Future Improvements

Potential future development includes:

* Provider dashboards
* Customer/provider role separation
* Real-time bid notifications
* Email notifications
* Payment processing
* Stripe integration
* REST API endpoints
* Laravel queues
* Redis caching
* WebSockets
* Laravel Policies
* AWS deployment
* CI/CD automation

## Project Status

This is an actively developed portfolio project focused on demonstrating practical Laravel backend and full-stack development.

The application is being developed incrementally, with automated tests added alongside new functionality.
