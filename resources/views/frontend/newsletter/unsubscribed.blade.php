@extends('layouts.app')

@section('title', 'Unsubscribed | Fancy Decorators')

@section('content')
<section class="unsubscribed-section py-5 section-bg" style="min-height: 60vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-0 p-5 shadow-lg bg-white rounded-4" data-aos="zoom-in">
                    <div class="text-secondary mb-4">
                        <i class="fa-solid fa-circle-minus fa-5x"></i>
                    </div>
                    <h1 class="h3 font-heading mb-3">You Have Been Unsubscribed</h1>
                    <p class="text-muted mb-4">
                        We have removed <strong>{{ $subscriber?->email }}</strong> from our mailing list. You will no longer receive marketing communications from us.
                    </p>

                    @if($subscriber)
                        <div class="mb-4 text-center">
                            <p class="small text-muted mb-2">Did you unsubscribe by mistake?</p>
                            <form method="POST" action="{{ route('newsletter.subscribe') }}" class="d-inline" id="resubscribeForm">
                                @csrf
                                <input type="hidden" name="email" value="{{ $subscriber->email }}">
                                <input type="hidden" name="name" value="{{ $subscriber->name }}">
                                <input type="hidden" name="privacy" value="1">
                                <button type="submit" class="btn btn-outline-dark btn-sm">
                                    <i class="fa-solid fa-arrow-rotate-left me-1"></i> Re-subscribe Instantly
                                </button>
                            </form>
                            <div id="resubscribe-message" class="small mt-2 d-none fw-semibold"></div>
                        </div>
                    @endif

                    <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2">
                        Go to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('resubscribeForm');
    const msg = document.getElementById('resubscribe-message');

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            msg.className = 'small mt-2 text-info fw-semibold';
            msg.textContent = 'Re-subscribing...';
            msg.classList.remove('d-none');

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire({
                        title: 'Re-subscribed!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: 'var(--button-background, #b19356)'
                    });
                    msg.className = 'small mt-2 text-success fw-semibold';
                    msg.textContent = 'Welcome back! You have re-subscribed.';
                    form.classList.add('d-none');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Unable to re-subscribe.',
                        icon: 'error',
                        confirmButtonColor: 'var(--button-background, #b19356)'
                    });
                    msg.className = 'small mt-2 text-danger fw-semibold';
                    msg.textContent = 'Failed to re-subscribe. Please try again.';
                }
            } catch (error) {
                msg.className = 'small mt-2 text-danger fw-semibold';
                msg.textContent = 'Connection error.';
            }
        });
    }
});
</script>
@endsection
