@extends('backend.app')

@section('title', 'Integration Settings')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="stripe-tab" data-bs-toggle="tab" data-bs-target="#stripe"
                                        type="button" role="tab" aria-controls="stripe"
                                        aria-selected="false">Stripe</button>
                                </li>
                            </ul>
                            <div class="tab-content mt-4" id="myTabContent">
                                <div class="tab-pane fade show active" id="stripe" role="tabpanel"
                                    aria-labelledby="stripe-tab">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Stripe Settings</h5>
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" action="{{ route('stripe.update') }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="row gy-4">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="STRIPE_KEY" class="form-label">Stripe Key:</label>
                                                            <input type="text"
                                                                class="form-control @error('STRIPE_KEY') is-invalid @enderror"
                                                                name="STRIPE_KEY" id="STRIPE_KEY"
                                                                placeholder="Please Enter Your Stripe Key"
                                                                value="{{ env('STRIPE_KEY') }}">
                                                            @error('STRIPE_KEY')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="STRIPE_SECRET" class="form-label">Stripe
                                                                Secret:</label>
                                                            <input type="text"
                                                                class="form-control @error('STRIPE_SECRET') is-invalid @enderror"
                                                                name="STRIPE_SECRET" id="STRIPE_SECRET"
                                                                placeholder="Please Enter Your Stripe Secret"
                                                                value="{{ env('STRIPE_SECRET') }}">
                                                            @error('STRIPE_SECRET')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <button type="submit" class="btn btn-primary">Save Stripe
                                                            Settings</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
