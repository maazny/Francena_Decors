@extends('layouts.app')

@section('title', ($job->seo_title ?: $job->title) . ' - Careers | Francena Decors')
@section('meta_description', $job->seo_description ?: ($job->short_description ?: Str::limit(strip_tags($job->description), 150)))
@section('meta_keywords', $job->seo_keywords ?: 'career vacancy, ' . $job->title . ', job application, hiring ' . $job->category?->name)
@section('canonical', route('careers.show', $job->slug))

@section('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "JobPosting",
  "title": "{{ $job->title }}",
  "description": "{!! clean(Str::limit(strip_tags($job->description), 250)) !!}",
  "datePosted": "{{ $job->published_at?->toIso8601String() ?? $job->created_at->toIso8601String() }}",
  @if($job->application_deadline)
  "validThrough": "{{ $job->application_deadline->toIso8601String() }}",
  @endif
  "employmentType": "{{ $job->employment_type }}",
  "hiringOrganization": {
    "@type": "Organization",
    "name": "Francena Decors",
    "sameAs": "{{ url('/') }}"
  },
  "jobLocation": {
    "@type": "Place",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "{{ $job->location?->city }}",
      "addressRegion": "{{ $job->location?->state }}",
      "addressCountry": "{{ $job->location?->country ?: 'USA' }}"
    }
  },
  @if($job->salary_to)
  "baseSalary": {
    "@type": "MonetaryAmount",
    "currency": "USD",
    "value": {
      "@type": "QuantitativeValue",
      "minValue": {{ $job->salary_from ?: 0 }},
      "maxValue": {{ $job->salary_to }},
      "unitText": "{{ strtoupper($job->salary_type ?: 'YEAR') }}"
    }
  },
  @endif
  "totalJobOpenings": {{ $job->vacancies ?: 1 }}
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "{{ url('/') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Careers",
      "item": "{{ route('careers.index') }}"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "Jobs",
      "item": "{{ route('careers.jobs') }}"
    },
    {
      "@type": "ListItem",
      "position": 4,
      "name": "{{ $job->title }}",
      "item": "{{ route('careers.show', $job->slug) }}"
    }
  ]
}
</script>
@endsection

@section('content')
<main style="py-5;">
  <div class="container py-5">
    
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-light"><i class="fa-solid fa-house me-1"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('careers.index') }}" class="text-decoration-none text-light">Careers</a></li>
        <li class="breadcrumb-item"><a href="{{ route('careers.jobs') }}" class="text-decoration-none text-light">Jobs</a></li>
        <li class="breadcrumb-item active text-warning text-truncate" aria-current="page" style="max-width: 250px;">{{ $job->title }}</li>
      </ol>
    </nav>

    <!-- Success / Error Alert boxes (Non-AJAX Fallbacks) -->
    @if(session('success'))
      <div class="alert alert-success border-0 rounded p-4 mb-4 text-white" style="background-color: #198754;">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
      </div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger border-0 rounded p-4 mb-4 text-white" style="background-color: #dc3545;">
        <i class="fa-solid fa-circle-exclamation me-2"></i> Please correct the highlighted errors inside the application form.
      </div>
    @endif

    <div class="row g-5">
      
      <!-- Primary Job Content Column -->
      <div class="col-lg-8">
        
        <!-- Header Meta Block -->
        <div class="mb-4">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-secondary text-uppercase">{{ $job->employment_type }}</span>
            <span class="badge bg-dark text-uppercase border border-secondary">{{ $job->experience_level }}</span>
            @if($job->featured)
              <span class="badge bg-gold text-uppercase text-white fw-bold">Featured</span>
            @endif
          </div>
          <h1 class="display-5 fw-bold font-serif mb-2" style="font-family: 'Playfair Display', serif; color: var(--gold);">{{ $job->title }}</h1>
          <p class="text-muted"><i class="fa-solid fa-building me-1"></i> {{ $job->department?->name }} • <i class="fa-solid fa-list me-1"></i> {{ $job->category?->name }}</p>
        </div>

        <hr class="border-secondary my-4">

        <!-- Short Description -->
        @if($job->short_description)
          <div class="p-4 mb-4 rounded glass-card border-l-primary" style="border-left: 4px solid var(--gold);">
            <h5 class="fw-semibold text-uppercase tracking-wider mb-2" style="font-size: 0.85rem; color: var(--gold);">Position Overview</h5>
            <p class="opacity-90 mb-0 font-italic">{{ $job->short_description }}</p>
          </div>
        @endif

        <!-- Full Rich Text Description -->
        <div class="job-description mb-5">
          <h3 class="h4 fw-bold font-serif mb-3">Job Description & Responsibilities</h3>
          <div class="opacity-90 leading-relaxed font-sans text-light">
            {!! $job->description !!}
          </div>
        </div>

        <!-- Dynamically sync'd Skills, Qualifications, Benefits lists -->
        @if($job->skills->count() > 0)
          <div class="mb-5">
            <h3 class="h4 fw-bold font-serif mb-3">Required Technical Skills</h3>
            <div class="d-flex flex-wrap gap-2">
              @foreach($job->skills as $skill)
                <span class="badge bg-dark text-white px-3 py-2 border border-secondary rounded-pill small">
                  <i class="fa-solid fa-circle-check text-warning me-1"></i> {{ $skill->skill_name }}
                </span>
              @endforeach
            </div>
          </div>
        @endif

        @if($job->qualifications->count() > 0)
          <div class="mb-5">
            <h3 class="h4 fw-bold font-serif mb-3">Qualifications & Requirements</h3>
            <ul class="list-unstyled">
              @foreach($job->qualifications as $qual)
                <li class="d-flex align-items-start mb-2.5 opacity-90">
                  <i class="fa-solid fa-square-check text-warning mt-1 me-2.5"></i>
                  <span>{{ $qual->qualification_name }}</span>
                </li>
              @endforeach
            </ul>
          </div>
        @endif

        @if($job->benefits->count() > 0)
          <div class="mb-5">
            <h3 class="h4 fw-bold font-serif mb-3">Position Benefits & Perks</h3>
            <div class="row g-3">
              @foreach($job->benefits as $benefit)
                <div class="col-md-6">
                  <div class="d-flex align-items-center p-3 rounded glass-card">
                    <i class="fa-solid fa-gift text-warning fa-lg me-3"></i>
                    <span class="fw-semibold small">{{ $benefit->benefit_name }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Social Share buttons -->
        <div class="d-flex align-items-center gap-3 py-4 border-top border-bottom border-secondary mb-5">
          <span class="small text-uppercase tracking-wider text-muted">Share Role:</span>
          <div class="d-inline-flex gap-2">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-sm btn-outline-light rounded-circle text-center" style="width: 36px; height: 36px; line-height: 24px;"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($job->title) }}" target="_blank" class="btn btn-sm btn-outline-light rounded-circle text-center" style="width: 36px; height: 36px; line-height: 24px;"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($job->title) }}" target="_blank" class="btn btn-sm btn-outline-light rounded-circle text-center" style="width: 36px; height: 36px; line-height: 24px;"><i class="fa-brands fa-linkedin-in"></i></a>
            <a href="mailto:?subject={{ rawurlencode('Job Opportunity: ' . $job->title) }}&body={{ rawurlencode('Check out this career opening at Francena Decors: ' . request()->url()) }}" class="btn btn-sm btn-outline-light rounded-circle text-center" style="width: 36px; height: 36px; line-height: 24px;"><i class="fa-solid fa-envelope"></i></a>
          </div>
        </div>

        <!-- ONLINE JOB APPLICATION FORM SECTION -->
        <section class="card border-0 glass-card p-4 p-md-5 mb-5" id="applySection">
          <h3 class="h3 fw-bold font-serif mb-2" style="font-family: 'Playfair Display', serif;">Apply For This Position</h3>
          <p class="text-muted mb-4">Please fill in your coordinates and upload your professional profile. All files are handled securely.</p>

          <form action="{{ route('careers.apply', $job->slug) }}" method="POST" enctype="multipart/form-data" id="jobApplicationForm">
            @csrf

            <div class="row g-4">
              <div class="col-md-6">
                <label for="fullName" class="form-label small text-uppercase fw-semibold opacity-75">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-0 bg-dark text-white rounded @error('full_name') is-invalid @enderror" id="fullName" name="full_name" value="{{ old('full_name') }}" required>
                @error('full_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="emailInput" class="form-label small text-uppercase fw-semibold opacity-75">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control border-0 bg-dark text-white rounded @error('email') is-invalid @enderror" id="emailInput" name="email" value="{{ old('email') }}" required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="phoneInput" class="form-label small text-uppercase fw-semibold opacity-75">Phone Number</label>
                <input type="text" class="form-control border-0 bg-dark text-white rounded @error('phone') is-invalid @enderror" id="phoneInput" name="phone" value="{{ old('phone') }}">
                @error('phone')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="currentCompany" class="form-label small text-uppercase fw-semibold opacity-75">Current Company</label>
                <input type="text" class="form-control border-0 bg-dark text-white rounded @error('current_company') is-invalid @enderror" id="currentCompany" name="current_company" value="{{ old('current_company') }}">
                @error('current_company')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="experienceInput" class="form-label small text-uppercase fw-semibold opacity-75">Years of Experience</label>
                <input type="number" step="0.1" min="0" class="form-control border-0 bg-dark text-white rounded @error('years_of_experience') is-invalid @enderror" id="experienceInput" name="years_of_experience" value="{{ old('years_of_experience') }}">
                @error('years_of_experience')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="salaryInput" class="form-label small text-uppercase fw-semibold opacity-75">Expected Salary ($ / yearly)</label>
                <input type="number" min="0" class="form-control border-0 bg-dark text-white rounded @error('expected_salary') is-invalid @enderror" id="salaryInput" name="expected_salary" value="{{ old('expected_salary') }}">
                @error('expected_salary')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <label for="coverLetter" class="form-label small text-uppercase fw-semibold opacity-75">Cover Letter / Notes</label>
                <textarea class="form-control border-0 bg-dark text-white rounded @error('cover_letter') is-invalid @enderror" id="coverLetter" name="cover_letter" rows="4" placeholder="Brief introduction..."></textarea>
                @error('cover_letter')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <label for="resumeInput" class="form-label small text-uppercase fw-semibold opacity-75">Attach Resume <span class="text-danger">*</span></label>
                <input type="file" class="form-control border-0 bg-dark text-white rounded @error('resume') is-invalid @enderror" id="resumeInput" name="resume" accept=".pdf,.doc,.docx" required>
                <div class="form-text small text-muted">Supports PDF, DOC, DOCX up to 5MB.</div>
                @error('resume')
                  <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="termsCheck" required>
                  <label class="form-check-label small text-muted" for="termsCheck">
                    I agree to the Terms & Conditions and consent to storing my data securely for recruitment evaluation.
                  </label>
                </div>
              </div>

              <div class="col-12 text-end">
                <button type="submit" class="btn btn-gold btn-lg rounded-pill px-5 shadow hover-scale" id="submitBtn">
                  <span class="normal-state"><i class="fa-solid fa-paper-plane me-2"></i> Submit Application</span>
                  <span class="loading-state d-none"><i class="fa-solid fa-spinner fa-spin me-2"></i> Processing...</span>
                </button>
              </div>
            </div>

          </form>
        </section>

      </div>

      <!-- Job Metadata Sidebar Column -->
      <div class="col-lg-4">
        
        <!-- Metadata Specs Card -->
        <div class="card border-0 glass-card p-4 mb-4">
          <h4 class="h5 fw-bold font-serif mb-4 pb-2 border-bottom border-secondary text-primary" style="color: var(--gold);">Position Overview</h4>
          
          <div class="mb-3">
            <span class="d-block small text-uppercase text-muted">Office Location</span>
            <span class="fw-semibold"><i class="fa-solid fa-location-dot me-1.5 text-warning"></i> {{ $job->location?->name }} ({{ $job->location?->city }})</span>
          </div>

          <div class="mb-3">
            <span class="d-block small text-uppercase text-muted">Employment Type</span>
            <span class="fw-semibold"><i class="fa-solid fa-briefcase me-1.5 text-warning"></i> {{ $job->employment_type }}</span>
          </div>

          <div class="mb-3">
            <span class="d-block small text-uppercase text-muted">Required Experience</span>
            <span class="fw-semibold"><i class="fa-solid fa-award me-1.5 text-warning"></i> {{ $job->experience_level }}</span>
          </div>

          <div class="mb-3">
            <span class="d-block small text-uppercase text-muted">Available Vacancies</span>
            <span class="fw-semibold"><i class="fa-solid fa-user-group me-1.5 text-warning"></i> {{ $job->vacancies }} position{{ $job->vacancies > 1 ? 's' : '' }}</span>
          </div>

          @if($job->salary_to)
            <div class="mb-3">
              <span class="d-block small text-uppercase text-muted">Salary Package</span>
              <span class="fw-bold" style="color: var(--gold);">
                <i class="fa-solid fa-money-bill-wave me-1.5 text-warning"></i>
                {{ $job->salary_from ? '$'.number_format($job->salary_from) . ' - ' : '' }}${{ number_format($job->salary_to) }}
              </span>
              <span class="small text-muted text-uppercase">/ {{ $job->salary_type ?: 'yearly' }}</span>
            </div>
          @endif

          @if($job->application_deadline)
            <div class="mb-4">
              <span class="d-block small text-uppercase text-muted">Application Deadline</span>
              <span class="fw-semibold text-danger"><i class="fa-solid fa-calendar-xmark me-1.5"></i> {{ $job->application_deadline->format('M d, Y') }}</span>
            </div>
          @endif

          <a href="#applySection" class="btn btn-gold w-100 rounded-pill py-2.5 shadow-sm sticky-apply-btn">Apply for Job</a>
        </div>

        <!-- Google Maps Iframe Embed if applicable -->
        @if($job->location?->google_map_embed)
          <div class="card border-0 glass-card overflow-hidden p-0 mb-4" style="height: 250px;">
            {!! $job->location->google_map_embed !!}
          </div>
        @endif

        <!-- Related Jobs list -->
        @if($relatedJobs->count() > 0)
          <div class="card border-0 glass-card p-4">
            <h4 class="h5 fw-bold font-serif mb-3 pb-2 border-bottom border-secondary">Related Jobs</h4>
            <div class="d-flex flex-column gap-3">
              @foreach($relatedJobs as $rel)
                <div class="p-2.5 rounded hover-bg-dark">
                  <span class="small text-muted text-uppercase" style="font-size: 0.65rem;">{{ $rel->department?->name }}</span>
                  <h6 class="fw-bold mb-1"><a href="{{ route('careers.show', $rel->slug) }}" class="text-white text-decoration-none hover-link">{{ $rel->title }}</a></h6>
                  <span class="small text-muted"><i class="fa-solid fa-location-dot text-warning"></i> {{ $rel->location??->city }}</span>
                </div>
              @endforeach
            </div>
          </div>
        @endif

      </div>

    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('jobApplicationForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Check if terms are ticked
        if (!document.getElementById('termsCheck').checked) {
          Swal.fire({
            icon: 'warning',
            title: 'Agreement Required',
            text: 'You must agree to the Terms & Conditions to submit your job application.',
            confirmButtonColor: '#d4af5f',
          });
          return;
        }

        // Show spinner state
        submitBtn.disabled = true;
        submitBtn.querySelector('.normal-state').classList.add('d-none');
        submitBtn.querySelector('.loading-state').classList.remove('d-none');

        // Clear existing errors
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        const formData = new FormData(form);

        try {
          const response = await fetch(form.action, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json',
            },
            body: formData,
          });

          const result = await response.json();

          if (response.ok && result.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: result.message || 'Your application has been received.',
              confirmButtonColor: '#d4af5f',
            }).then(() => {
              form.reset();
              window.scrollTo({ top: 0, behavior: 'smooth' });
            });
          } else {
            // Handle validation errors
            if (response.status === 422 && result.errors) {
              Object.keys(result.errors).forEach(field => {
                const fieldName = field.replace('_', ' ');
                const messages = result.errors[field];
                
                // Try finding field element
                const input = form.querySelector(`[name="${field}"]`) || form.querySelector(`[name="${field}[]"]`);
                if (input) {
                  input.classList.add('is-invalid');
                  const errDiv = document.createElement('div');
                  errDiv.className = 'invalid-feedback text-danger d-block';
                  errDiv.textContent = messages[0];
                  input.parentNode.appendChild(errDiv);
                } else {
                  // Fallback global alert if field element not found
                  Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: messages[0],
                    confirmButtonColor: '#d4af5f',
                  });
                }
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: result.message || 'Something went wrong. Please try again.',
                confirmButtonColor: '#d4af5f',
              });
            }
          }
        } catch (err) {
          console.error(err);
          Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'Please check your connection and try again.',
            confirmButtonColor: '#d4af5f',
          });
        } finally {
          // Restore button state
          submitBtn.disabled = false;
          submitBtn.querySelector('.normal-state').classList.remove('d-none');
          submitBtn.querySelector('.loading-state').classList.add('d-none');
        }
      });
    }
  });
</script>

<style>
  .hover-link {
    transition: color 0.2s ease;
  }
  .hover-link:hover {
    color: var(--gold) !important;
  }
  .hover-bg-dark {
    transition: background-color 0.2s ease;
  }
  .hover-bg-dark:hover {
    background-color: rgba(255, 255, 255, 0.05);
  }
  .border-l-primary {
    border-left: 4px solid var(--gold);
  }
  .sticky-apply-btn {
    transition: transform 0.2s ease;
  }
  .sticky-apply-btn:hover {
    transform: translateY(-2px);
  }
  iframe {
    width: 100% !important;
    height: 100% !important;
    border: 0 !important;
  }
</style>
@endpush
