@extends('layouts.app')

@section('title', 'Find Jobs - Careers Portal | Fancy Decorators')
@section('meta_description', 'Search and filter active job openings at Fancy Decorators. Discover full-time, part-time, and custom roles in design, architecture, and construction.')
@section('meta_keywords', 'jobs, job vacancies, builder hiring, interior designer, construction engineering vacancies')

@section('content')
<main style="background-color: var(--background-color, #121212); color: var(--text-color, #ffffff); min-height: 100vh; py-5;">
  
  <div class="container py-5">
    
    <!-- Page Header & Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-light"><i class="fa-solid fa-house me-1"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('careers.index') }}" class="text-decoration-none text-light">Careers</a></li>
        <li class="breadcrumb-item active text-white-50" aria-current="page">Jobs</li>
      </ol>
    </nav>

    <div class="row align-items-center mb-5">
      <div class="col-md-6">
        <h1 class="display-5 fw-bold font-serif" style="font-family: 'Playfair Display', serif; color: var(--button-background, #d4af5f);">Open Positions</h1>
        <p class="text-muted mb-0">Discover your next professional milestone at Fancy Decorators.</p>
      </div>
      <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <div class="d-inline-flex gap-2">
          <button id="layoutGrid" class="btn btn-primary btn-sm px-3 rounded-pill active" title="Grid View"><i class="fa-solid fa-grid-2"></i> Grid</button>
          <button id="layoutList" class="btn btn-outline-light btn-sm px-3 rounded-pill" title="List View"><i class="fa-solid fa-list"></i> List</button>
        </div>
      </div>
    </div>

    <div class="row g-4">
      
      <!-- Filter Sidebar Column -->
      <div class="col-lg-4">
        <div class="card border-0 glass-card p-4 sticky-top" style="top: 90px; z-index: 10;">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><i class="fa-solid fa-filter me-2 text-primary" style="color: var(--button-background, #d4af5f) !important;"></i> Filter Jobs</h5>
            <a href="{{ route('careers.jobs') }}" class="text-decoration-none text-muted small">Clear All</a>
          </div>

          <form action="{{ route('careers.jobs') }}" method="GET" id="filterForm">
            <!-- Hidden inputs to preserve layout & sort -->
            <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}" id="sortInput">

            <!-- Search input -->
            <div class="mb-4">
              <label for="searchInput" class="form-label small text-uppercase fw-semibold opacity-75">Keyword Search</label>
              <div class="input-group">
                <span class="input-group-text bg-dark border-0 text-muted"><i class="fa-solid fa-search"></i></span>
                <input type="text" id="searchInput" name="search" class="form-control border-0 bg-dark text-white" placeholder="Title, key skill, etc..." value="{{ request('search') }}">
              </div>
            </div>

            <!-- Department filter -->
            <div class="mb-4">
              <label for="deptSelect" class="form-label small text-uppercase fw-semibold opacity-75">Department</label>
              <select id="deptSelect" name="department_id" class="form-select border-0 bg-dark text-white">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                  <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
                @endforeach
              </select>
            </div>

            <!-- Category filter -->
            <div class="mb-4">
              <label for="catSelect" class="form-label small text-uppercase fw-semibold opacity-75">Category</label>
              <select id="catSelect" name="category_id" class="form-select border-0 bg-dark text-white">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>

            <!-- Location filter -->
            <div class="mb-4">
              <label for="locSelect" class="form-label small text-uppercase fw-semibold opacity-75">Office Location</label>
              <select id="locSelect" name="location_id" class="form-select border-0 bg-dark text-white">
                <option value="">All Locations</option>
                @foreach($locations as $loc)
                  <option value="{{ $loc->id }}" @selected(request('location_id') == $loc->id)>{{ $loc->name }} ({{ $loc->city }})</option>
                @endforeach
              </select>
            </div>

            <!-- Employment Type -->
            <div class="mb-4">
              <label class="form-label small text-uppercase fw-semibold opacity-75 d-block">Employment Type</label>
              @foreach($employmentTypes as $type)
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="employment_type" id="type_{{ Str::slug($type) }}" value="{{ $type }}" @checked(request('employment_type') === $type)>
                  <label class="form-check-label small" for="type_{{ Str::slug($type) }}">{{ $type }}</label>
                </div>
              @endforeach
              @if(request('employment_type'))
                <div class="form-check mt-1">
                  <input class="form-check-input" type="radio" name="employment_type" id="type_clear" value="">
                  <label class="form-check-label small text-muted" for="type_clear">Clear type filter</label>
                </div>
              @endif
            </div>

            <!-- Experience Level -->
            <div class="mb-4">
              <label class="form-label small text-uppercase fw-semibold opacity-75 d-block">Experience Level</label>
              @foreach($experienceLevels as $lvl)
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="experience_level" id="lvl_{{ Str::slug($lvl) }}" value="{{ $lvl }}" @checked(request('experience_level') === $lvl)>
                  <label class="form-check-label small" for="lvl_{{ Str::slug($lvl) }}">{{ $lvl }}</label>
                </div>
              @endforeach
              @if(request('experience_level'))
                <div class="form-check mt-1">
                  <input class="form-check-input" type="radio" name="experience_level" id="lvl_clear" value="">
                  <label class="form-check-label small text-muted" for="lvl_clear">Clear experience filter</label>
                </div>
              @endif
            </div>

            <!-- Salary Filter -->
            <div class="mb-4">
              <label for="salaryRange" class="form-label small text-uppercase fw-semibold opacity-75 d-flex justify-content-between">
                <span>Minimum Salary</span>
                <span id="salaryVal" style="color: var(--button-background, #d4af5f);">{{ request('salary_min') ? '$' . number_format(request('salary_min')) : 'Any' }}</span>
              </label>
              <input type="range" class="form-range" id="salaryRange" name="salary_min" min="0" max="250000" step="10000" value="{{ request('salary_min', 0) }}">
            </div>

            <!-- Featured check -->
            <div class="form-check form-switch mb-4">
              <input class="form-check-input" type="checkbox" id="featuredCheck" name="featured" value="1" @checked(request('featured'))>
              <label class="form-check-label small text-uppercase fw-semibold opacity-75" for="featuredCheck">Featured Jobs Only</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 shadow-sm">
              <i class="fa-solid fa-filter me-2"></i> Apply Filters
            </button>
          </form>
        </div>
      </div>

      <!-- Jobs Grid/List Output Column -->
      <div class="col-lg-8">
        
        <!-- Sorting Panel -->
        <div class="d-flex justify-content-between align-items-center mb-4 bg-dark p-3 rounded glass-card">
          <div class="small opacity-75">
            Found {{ $jobs->total() }} openings matching requirements.
          </div>
          <div class="d-flex align-items-center gap-2">
            <label for="sortDropdown" class="small text-nowrap text-muted mb-0">Sort By:</label>
            <select id="sortDropdown" class="form-select form-select-sm border-0 bg-dark text-white rounded-pill px-3" style="width: 140px;">
              <option value="latest" @selected(request('sort') === 'latest')>Latest Post</option>
              <option value="salary_high" @selected(request('sort') === 'salary_high')>High Salary</option>
              <option value="vacancies" @selected(request('sort') === 'vacancies')>Most Vacancies</option>
            </select>
          </div>
        </div>

        @if($jobs->count() > 0)
          <!-- Grid wrapper -->
          <div class="row g-4" id="jobsContainer">
            @foreach($jobs as $job)
              <div class="col-md-6 job-item-wrapper" data-layout-item>
                <div class="card h-100 border-0 glass-card p-4 position-relative d-flex flex-column">
                  @if($job->featured)
                    <span class="position-absolute top-0 end-0 bg-primary px-3 py-1 rounded-bottom text-uppercase text-white small" style="background-color: var(--button-background, #d4af5f) !important;">Featured</span>
                  @endif
                  
                  <span class="small opacity-75 text-uppercase fw-semibold" style="color: var(--button-background, #d4af5f);">{{ $job->department?->name }}</span>
                  <h4 class="h5 fw-bold my-2">
                    <a href="{{ route('careers.show', $job->slug) }}" class="text-white text-decoration-none hover-link">{{ $job->title }}</a>
                  </h4>

                  <div class="d-flex align-items-center gap-2 mb-3 text-muted small flex-wrap">
                    <span><i class="fa-solid fa-location-dot"></i> {{ $job->location?->city }}</span>
                    <span>•</span>
                    <span><i class="fa-solid fa-clock"></i> {{ $job->employment_type }}</span>
                    <span>•</span>
                    <span><i class="fa-solid fa-briefcase"></i> {{ $job->experience_level }}</span>
                  </div>

                  <p class="opacity-75 small line-clamp-3 mb-4">{{ $job->short_description ?: Str::limit(strip_tags($job->description), 120) }}</p>

                  <div class="mt-auto pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                    <div>
                      @if($job->salary_to)
                        <span class="fw-bold d-block text-white" style="color: var(--button-background, #d4af5f) !important;">
                          {{ $job->salary_from ? '$'.number_format($job->salary_from) . ' - ' : '' }}${{ number_format($job->salary_to) }}
                        </span>
                        <span class="text-muted small text-uppercase" style="font-size: 0.65rem;">{{ $job->salary_type ?: 'yearly' }}</span>
                      @else
                        <span class="text-muted small">Competitive Salary</span>
                      @endif
                    </div>
                    <a href="{{ route('careers.show', $job->slug) }}" class="btn btn-sm btn-primary px-4 rounded-pill">Apply</a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-center mt-5">
            {{ $jobs->links() }}
          </div>
        @else
          <div class="text-center py-5 glass-card rounded p-5">
            <i class="fa-solid fa-folder-open fa-3x mb-3 text-muted"></i>
            <h4 class="fw-bold">No Openings Match Your Criteria</h4>
            <p class="text-muted max-w-sm mx-auto">Try resetting some filters or search query to explore other available job positions.</p>
            <a href="{{ route('careers.jobs') }}" class="btn btn-outline-primary px-4 py-2 mt-2 rounded-pill">Reset All Filters</a>
          </div>
        @endif

      </div>

    </div>
  </div>

</main>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Salary range label display updater
    const range = document.getElementById('salaryRange');
    const valSpan = document.getElementById('salaryVal');
    if (range && valSpan) {
      range.addEventListener('input', (e) => {
        const val = e.target.value;
        valSpan.textContent = val > 0 ? '$' + Number(val).toLocaleString() : 'Any';
      });
    }

    // Sort update trigger
    const sortDropdown = document.getElementById('sortDropdown');
    const sortInput = document.getElementById('sortInput');
    const filterForm = document.getElementById('filterForm');
    if (sortDropdown && sortInput && filterForm) {
      sortDropdown.addEventListener('change', () => {
        sortInput.value = sortDropdown.value;
        filterForm.submit();
      });
    }

    // Grid / List Toggle layout
    const layoutGridBtn = document.getElementById('layoutGrid');
    const layoutListBtn = document.getElementById('layoutList');
    const container = document.getElementById('jobsContainer');

    if (layoutGridBtn && layoutListBtn && container) {
      layoutGridBtn.addEventListener('click', () => {
        layoutGridBtn.classList.add('btn-primary', 'active');
        layoutGridBtn.classList.remove('btn-outline-light');
        layoutListBtn.classList.add('btn-outline-light');
        layoutListBtn.classList.remove('btn-primary', 'active');

        container.className = "row g-4";
        document.querySelectorAll('[data-layout-item]').forEach(item => {
          item.className = "col-md-6 job-item-wrapper";
        });
      });

      layoutListBtn.addEventListener('click', () => {
        layoutListBtn.classList.add('btn-primary', 'active');
        layoutListBtn.classList.remove('btn-outline-light');
        layoutGridBtn.classList.add('btn-outline-light');
        layoutGridBtn.classList.remove('btn-primary', 'active');

        container.className = "row g-3 flex-column";
        document.querySelectorAll('[data-layout-item]').forEach(item => {
          item.className = "col-12 job-item-wrapper";
        });
      });
    }
  });
</script>

<style>
  .hover-link {
    transition: color 0.2s ease;
  }
  .hover-link:hover {
    color: var(--button-background, #d4af5f) !important;
  }
  .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .job-item-wrapper {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .job-item-wrapper:hover {
    transform: translateY(-4px);
  }
</style>
@endpush
