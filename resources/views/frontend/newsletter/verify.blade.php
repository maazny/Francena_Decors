@extends('layouts.app')

@section('title', $success ? 'Subscription Verified | Fancy Decorators' : 'Verification Failed | Fancy Decorators')

@section('content')
<section class="verify-section py-5 section-bg" style="min-height: 60vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-0 p-5 shadow-lg bg-white rounded-4" data-aos="zoom-in">
                    @if($success)
                        <div class="text-success mb-4">
                            <i class="fa-solid fa-circle-check fa-5x"></i>
                        </div>
                        <h1 class="h3 font-heading mb-3">Subscription Confirmed!</h1>
                        <p class="text-muted mb-4">
                            Thank you for verifying your email address. You have successfully subscribed to the Fancy Decorators newsletter.
                        </p>
                        
                        @if($subscriber)
                            <div class="mb-4">
                                <a href="{{ URL::signedRoute('newsletter.preferences', ['token' => $subscriber->unsubscribe_token]) }}" class="btn btn-outline-dark btn-sm">
                                    <i class="fa-solid fa-sliders me-1"></i> Manage Subscription Preferences
                                </a>
                            </div>
                        @endif

                        <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2">
                            Go to Homepage
                        </a>
                    @else
                        <div class="text-danger mb-4">
                            <i class="fa-solid fa-triangle-exclamation fa-5x"></i>
                        </div>
                        <h1 class="h3 font-heading mb-3">Verification Failed</h1>
                        <p class="text-muted mb-4">
                            The verification link is invalid, expired, or has already been used. Please try subscribing again.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary px-3 py-2">Back Home</a>
                            <a href="{{ route('newsletter.subscribe-form') }}" class="btn btn-primary px-4 py-2" style="background-color: var(--button-background, #b19356); border-color: var(--button-background, #b19356);">Subscribe Again</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
