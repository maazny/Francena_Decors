@extends('layouts.app')

@section('title', 'Unsubscribe from Newsletter | Francena Decors')

@section('content')
<section class="unsubscribe-section py-5 section-bg" style="min-height: 60vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card border-0 p-5 shadow-lg bg-white rounded-4" data-aos="fade-up">
                    <h1 class="h3 font-heading mb-3 text-center text-dark">Unsubscribe Request</h1>
                    <p class="text-muted mb-4 text-center">
                        We are sorry to see you go! We value your feedback. Please let us know why you are choosing to opt-out.
                    </p>

                    <form method="POST" action="{{ route('newsletter.post-unsubscribe', $subscriber->unsubscribe_token) }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Please select a reason:</label>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="reason" id="reason_frequent" value="too_frequent" checked>
                                <label class="form-check-label" for="reason_frequent">
                                    Emails are too frequent
                                </label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="reason" id="reason_irrelevant" value="irrelevant">
                                <label class="form-check-label" for="reason_irrelevant">
                                    The content is no longer relevant to me
                                </label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="reason" id="reason_accidental" value="accidental">
                                <label class="form-check-label" for="reason_accidental">
                                    I signed up by mistake
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="reason" id="reason_other" value="other">
                                <label class="form-check-label" for="reason_other">
                                    Other reason
                                </label>
                            </div>
                        </div>

                        <div id="other_reason_wrapper" class="mb-4 d-none">
                            <label for="other_reason" class="form-label small text-muted">Please specify:</label>
                            <textarea name="other_reason" id="other_reason" class="form-control" rows="3" placeholder="Tell us more..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary px-3 py-2">Cancel</a>
                            <button type="submit" class="btn btn-danger px-4 py-2">
                                Unsubscribe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const reasonRadios = document.querySelectorAll('input[name="reason"]');
    const otherWrapper = document.getElementById('other_reason_wrapper');
    const otherTextarea = document.getElementById('other_reason');

    reasonRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'other') {
                otherWrapper.classList.remove('d-none');
                otherTextarea.setAttribute('required', 'required');
            } else {
                otherWrapper.classList.add('d-none');
                otherTextarea.removeAttribute('required');
            }
        });
    });
});
</script>
@endsection
