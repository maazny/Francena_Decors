@props([
    'layout' => 'standard', // 'standard', 'inline', 'sidebar'
    'title' => 'Subscribe to Our Newsletter',
    'subtitle' => 'Get premium design tips, luxury trends, and company updates delivered to your inbox.',
    'showName' => true,
    'showPhone' => false,
    'showLanguage' => false,
])

<div class="newsletter-component newsletter-layout-{{ $layout }}" id="newsletter-form-container">
    @if($layout !== 'inline')
        <div class="newsletter-header mb-4">
            <h3 class="h4 font-heading text-primary mb-2">{{ $title }}</h3>
            <p class="text-muted small mb-0">{{ $subtitle }}</p>
        </div>
    @endif

    <form class="newsletter-ajax-form" action="{{ route('newsletter.subscribe') }}" method="POST" data-layout="{{ $layout }}">
        @csrf
        <div class="row g-3">
            @if($showName)
                <div class="{{ $layout === 'inline' ? 'col-md-3' : 'col-12' }}">
                    <label for="newsletter_name" class="form-label visually-hidden">Full Name</label>
                    <input type="text" name="name" id="newsletter_name" class="form-control" placeholder="Full Name" required aria-required="true">
                </div>
            @endif

            <div class="{{ $layout === 'inline' ? 'col-md-4' : 'col-12' }}">
                <label for="newsletter_email" class="form-label visually-hidden">Email Address</label>
                <input type="email" name="email" id="newsletter_email" class="form-control" placeholder="Email Address" required aria-required="true">
            </div>

            @if($showPhone)
                <div class="{{ $layout === 'inline' ? 'col-md-3' : 'col-12' }}">
                    <label for="newsletter_phone" class="form-label visually-hidden">Phone Number</label>
                    <input type="text" name="phone" id="newsletter_phone" class="form-control" placeholder="Phone Number (Optional)">
                </div>
            @endif

            @if($showLanguage)
                <div class="{{ $layout === 'inline' ? 'col-md-2' : 'col-12' }}">
                    <label for="newsletter_lang" class="form-label visually-hidden">Preferred Language</label>
                    <select name="preferred_language" id="newsletter_lang" class="form-select">
                        <option value="en">English</option>
                        <option value="es">Español</option>
                        <option value="fr">Français</option>
                    </select>
                </div>
            @endif

            <div class="{{ $layout === 'inline' ? 'col-md-2' : 'col-12' }}">
                <button type="submit" class="btn btn-primary w-100 h-100 py-2">
                    <span class="submit-text">Subscribe</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>

            <div class="col-12">
                <div class="form-check text-start">
                    <input type="checkbox" name="privacy" id="newsletter_privacy" class="form-check-input" required aria-required="true">
                    <label for="newsletter_privacy" class="form-check-label small text-muted">
                        I agree to the <a href="/privacy-policy" target="_blank">Privacy Policy</a> and consent to receiving marketing emails.
                    </label>
                </div>
            </div>
        </div>
    </form>
</div>

@once
@push('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // If SweetAlert2 is not loaded yet (since the push directive might run after body scripts), load it dynamically
    if (typeof Swal === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.head.appendChild(script);
    }

    document.querySelectorAll('.newsletter-ajax-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const submitText = submitBtn.querySelector('.submit-text');
            const spinner = submitBtn.querySelector('.spinner-border');
            
            // Disable button and show spinner
            submitBtn.disabled = true;
            submitText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: 'var(--button-background, #b19356)',
                    });
                    form.reset();
                } else {
                    let errMsg = data.message || 'Validation error.';
                    if (data.errors) {
                        errMsg = Object.values(data.errors).map(err => err[0]).join('\n');
                    }
                    Swal.fire({
                        title: 'Error!',
                        text: errMsg,
                        icon: 'error',
                        confirmButtonColor: 'var(--button-background, #b19356)',
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: 'Connection Error',
                    text: 'Unable to connect to server. Please try again later.',
                    icon: 'error',
                    confirmButtonColor: 'var(--button-background, #b19356)',
                });
            } finally {
                submitBtn.disabled = false;
                submitText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        });
    });
});
</script>
@endpush
@endonce
