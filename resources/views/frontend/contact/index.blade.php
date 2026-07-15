@extends('layouts.app')

@section('title', 'Contact Us - Francena Decors | Luxury Construction & Design')
@section('meta_description', 'Contact Francena Decors. Inquire about our custom luxury construction, premium residential and commercial builds, renovations, or interior design services.')
@section('meta_keywords', 'contact francena decors, luxury construction contact, hire builder, commercial contractor inquiry')
@section('canonical', route('contact.index'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "ContactPage",
  "name": "Contact Francena Decors",
  "description": "Contact our team for premium luxury construction, renovations, and custom interior design inquiries.",
  "url": "{{ route('contact.index') }}",
  "mainEntity": {
    "@@type": "HomeAndConstructionBusiness",
    "name": "{{ $siteSetting->company_name ?: 'Francena Decors' }}",
    "telephone": "{{ $siteSetting->phone ?: '+1 234 567 890' }}",
    "email": "{{ $siteSetting->company_email ?: 'info@francenadecors.com' }}",
    "address": {
      "@@type": "PostalAddress",
      "streetAddress": "{{ $siteSetting->address ?: '25 Royal Avenue' }}",
      "addressLocality": "{{ $siteSetting->city ?: 'Downtown City' }}",
      "addressRegion": "{{ $siteSetting->state ?: 'State' }}",
      "postalCode": "{{ $siteSetting->postal_code ?: '12345' }}",
      "addressCountry": "{{ $siteSetting->country ?: 'USA' }}"
    }
  }
}
</script>
@endsection

@section('content')
<!-- Page Banner -->
<section class="py-5 bg-dark text-white text-center position-relative" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)); background-size: cover; background-position: center;">
  <div class="container py-4">
    <span class="text-uppercase tracking-wider small text-warning mb-2 d-block">Get In Touch</span>
    <h1 class="display-4 fw-bold mb-3">Contact Us</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active text-warning" aria-current="page">Contact</li>
      </ol>
    </nav>
  </div>
</section>

<!-- Company Introduction Intro Block -->
<section class="py-5 bg-dark text-white text-center border-bottom border-secondary">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <span class="text-warning small text-uppercase tracking-wider mb-2 d-block">Francena Decors Philosophy</span>
        <h2 class="h3 fw-bold mb-3">Crafting Landmarks of Luxury & Distinction</h2>
        <p class="text-white-50 lead fs-6">
          At Francena Decors, we shape luxurious spaces and build landmarks of distinction. Whether you are seeking a custom residential build, a sophisticated commercial fit-out, or elite interior styling, our specialists are ready to consult on your vision.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section class="py-5 text-white bg-dark">
  <div class="container py-4">
    <div class="row g-5">
      <!-- Left Column: Contact Form -->
      <div class="col-lg-7">
        <div class="glass-card p-4 p-md-5 rounded shadow-lg border border-secondary" style="background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px);">
          <h2 class="h3 fw-bold mb-2">Send Us a Message</h2>
          <p class="text-white-50 mb-4">Complete the inquiry form below, and a lead coordinator will contact you within 24 hours.</p>

          <form id="contact-form" action="{{ route('contact.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small text-white-50">Full Name *</label>
                <input type="text" name="name" class="form-control bg-dark text-white border-secondary" required placeholder="e.g. Sarah Connor">
              </div>
              
              <div class="col-md-6">
                <label class="form-label small text-white-50">Email Address *</label>
                <input type="email" name="email" class="form-control bg-dark text-white border-secondary" required placeholder="sarah@example.com">
              </div>

              <div class="col-md-6">
                <label class="form-label small text-white-50">Phone Number</label>
                <input type="text" name="phone" class="form-control bg-dark text-white border-secondary" placeholder="+1 (555) 123-4567">
              </div>

              <div class="col-md-6">
                <label class="form-label small text-white-50">Company Name</label>
                <input type="text" name="company" class="form-control bg-dark text-white border-secondary" placeholder="Cyberdyne Systems">
              </div>

              <div class="col-md-6">
                <label class="form-label small text-white-50">Subject *</label>
                <input type="text" name="subject" class="form-control bg-dark text-white border-secondary" required placeholder="Inquiry Subject">
              </div>

              <div class="col-md-6">
                <label class="form-label small text-white-50">Inquiry Category *</label>
                <select name="contact_category_id" class="form-select bg-dark text-white border-secondary" required>
                  <option value="">Select Category</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-12">
                <label class="form-label small text-white-50">Message *</label>
                <textarea name="message" class="form-control bg-dark text-white border-secondary" rows="5" required placeholder="Describe your luxury construction, renovation, or design project details..."></textarea>
              </div>

              <div class="col-12">
                <label class="form-label small text-white-50">Upload Attachment (Optional)</label>
                <input type="file" name="attachment" class="form-control bg-dark text-white border-secondary">
                <small class="text-white-50 d-block mt-1">Accepted: PDF, DOC, DOCX, Images. Max 10MB.</small>
              </div>

              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="consentCheckbox" required>
                  <label class="form-check-label small text-white-50" for="consentCheckbox">
                    I consent to having this website store my submitted information to answer my inquiry. *
                  </label>
                </div>
              </div>

              <div class="col-12 mt-4">
                <button type="submit" id="submit-btn" class="btn btn-warning w-100 text-dark fw-bold py-2">
                  Send Inquiries Request
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Right Column: Contact Details & Map & Branches -->
      <div class="col-lg-5">
        <div class="card bg-transparent border-0 h-100">
          <h2 class="h3 fw-bold mb-4">Contact Details</h2>

          <div class="vstack gap-4 mb-4">
            <!-- Office Address -->
            <div class="d-flex align-items-start">
              <div class="text-warning me-3 fs-4">
                <i class="bi bi-geo-alt-fill"></i>
              </div>
              <div>
                <h5 class="h6 fw-bold mb-1">Our Main Office</h5>
                <p class="text-white-50 mb-0">
                  {{ $siteSetting->address ?: '25 Royal Avenue' }}<br>
                  {{ $siteSetting->city ?: 'Downtown City' }}, {{ $siteSetting->state ?: 'State' }} {{ $siteSetting->postal_code ?: '12345' }}<br>
                  {{ $siteSetting->country ?: 'United States' }}
                </p>
              </div>
            </div>

            <!-- Email Inquiries -->
            <div class="d-flex align-items-start">
              <div class="text-warning me-3 fs-4">
                <i class="bi bi-envelope-fill"></i>
              </div>
              <div>
                <h5 class="h6 fw-bold mb-1">Email Inquiries</h5>
                <p class="text-white-50 mb-0">
                  General: <a href="mailto:{{ $siteSetting->company_email ?: 'info@francenadecors.com' }}" class="text-warning text-decoration-none">{{ $siteSetting->company_email ?: 'info@francenadecors.com' }}</a><br>
                  Support: <a href="mailto:{{ $siteSetting->support_email ?: 'support@francenadecors.com' }}" class="text-warning text-decoration-none">{{ $siteSetting->support_email ?: 'support@francenadecors.com' }}</a>
                </p>
              </div>
            </div>

            <!-- Phone Callbacks -->
            <div class="d-flex align-items-start">
              <div class="text-warning me-3 fs-4">
                <i class="bi bi-telephone-fill"></i>
              </div>
              <div>
                <h5 class="h6 fw-bold mb-1">Phone Numbers</h5>
                <p class="text-white-50 mb-0">
                  Office: <a href="tel:{{ $siteSetting->phone ?: '+1234567890' }}" class="text-warning text-decoration-none">{{ $siteSetting->phone ?: '+1 234 567 890' }}</a><br>
                  Mobile: <a href="tel:{{ $siteSetting->mobile ?: '+1987654321' }}" class="text-warning text-decoration-none">{{ $siteSetting->mobile ?: '+1 987 654 321' }}</a>
                </p>
              </div>
            </div>

            <!-- Office hours -->
            <div class="d-flex align-items-start">
              <div class="text-warning me-3 fs-4">
                <i class="bi bi-clock-fill"></i>
              </div>
              <div>
                <h5 class="h6 fw-bold mb-1">Business Hours</h5>
                <p class="text-white-50 mb-0">
                  {{ $siteSetting->office_hours ?: 'Monday - Friday: 9:00 AM - 6:00 PM' }}
                </p>
              </div>
            </div>
          </div>

          <!-- Emergency Contact box -->
          <div class="p-3 mb-4 rounded border border-warning" style="background: rgba(212, 175, 95, 0.05);">
            <div class="d-flex align-items-center mb-1">
              <i class="bi bi-exclamation-triangle-fill text-warning me-2 fs-5"></i>
              <h6 class="mb-0 fw-bold text-warning">Urgent Consultation Hotline</h6>
            </div>
            <p class="small text-white-50 mb-0">
              Need immediate design support? Call our priority line: <a href="tel:+1800555GOLD" class="text-warning fw-bold text-decoration-none">+1 800 555-GOLD</a> (Active 24/7 for premium clients).
            </p>
          </div>

          <!-- Office Locations (Future-ready) -->
          <div class="mb-4">
            <h5 class="h6 fw-bold mb-2">Our Branch Network</h5>
            <ul class="list-unstyled text-white-50 small mb-0">
              <li class="mb-1"><i class="bi bi-circle-fill text-warning me-2" style="font-size: 0.5rem;"></i><strong>Headquarters & Showroom</strong>: 25 Royal Avenue, Downtown City</li>
              <li><i class="bi bi-circle-fill text-warning me-2" style="font-size: 0.5rem;"></i><strong>Design Studio</strong>: 100 Creative Plaza, Uptown District</li>
            </ul>
          </div>

          <!-- Dynamic Google Maps iframe embed -->
          @if($siteSetting->google_map)
            <div class="rounded overflow-hidden shadow-lg border border-secondary" style="min-height: 220px;">
              {!! $siteSetting->google_map !!}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call-to-Action Section -->
<section class="py-5 bg-dark border-top border-secondary text-white text-center">
  <div class="container py-3">
    <h2 class="h3 fw-bold mb-3">Let's Build Something Extraordinary Together</h2>
    <p class="text-white-50 mb-4 justify-content-center d-flex mx-auto col-md-8">
      Ready to start your upscale residential or commercial project? Browse our portfolio to see how we bring elite concepts to life.
    </p>
    <div class="d-flex gap-3 justify-content-center">
      <a href="{{ route('projects.index') }}" class="btn btn-warning text-dark fw-bold px-4 py-2">Explore Featured Projects</a>
      <a href="{{ route('services.index') }}" class="btn btn-outline-light px-4 py-2">Our Services</a>
    </div>
  </div>
</section>

<!-- SweetAlert2 AJAX Form Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');

    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Show loader
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

      const formData = new FormData(contactForm);

      try {
        const response = await fetch(contactForm.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        });

        const data = await response.json();

        if (response.ok && data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Message Sent!',
            text: data.message || 'Thank you for getting in touch.',
            confirmButtonColor: '#d4af5f'
          });
          contactForm.reset();
        } else {
          let errorMessage = 'Something went wrong. Please check your form details.';
          if (data.errors) {
            errorMessage = Object.values(data.errors).flat().join('<br>');
          }
          Swal.fire({
            icon: 'error',
            title: 'Validation Failed',
            html: errorMessage,
            confirmButtonColor: '#d4af5f'
          });
        }
      } catch (err) {
        console.error(err);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Network error occurred. Please try again.',
          confirmButtonColor: '#d4af5f'
        });
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Send Inquiries Request';
      }
    });
  });
</script>
@endsection
