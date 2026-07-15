@extends('layouts.app')

@section('title', 'Meet Our Team | Francena Decors')

@section('content')
<!-- Page Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(180deg, rgba(8, 7, 10, 0.6), rgba(5, 4, 7, 0.95)), url('https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1950&q=80') no-repeat center center/cover; min-height: 35vh; display: flex; align-items: center;">
  <div class="container text-center py-4">
    <span class="text-uppercase tracking-wider small text-warning mb-2 d-block">Elite Creators</span>
    <h1 class="display-4 fw-bold mb-3 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">Our Team</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active text-warning" aria-current="page">Team</li>
      </ol>
    </nav>
  </div>
</section>

<section class="py-5" style="min-height: 60vh;">
  <div class="container">
    
    <!-- Filter Search Form -->
    <div class="card border-0 glass-card p-4 mb-5 shadow-lg">
      <form method="GET" class="row g-3 align-items-center">
        <div class="col-md-5">
          <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Search members by name, role..." value="{{ $search }}">
        </div>
        <div class="col-md-5">
          <select name="department" class="form-select bg-dark text-white border-secondary">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
              <option value="{{ $dept->id }}" @selected($department == $dept->id)>{{ $dept->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-gold w-100 py-2">
            <i class="fas fa-search me-2"></i>Filter
          </button>
        </div>
      </form>
    </div>

    <!-- Team Members Grid -->
    <div class="row g-4">
      @forelse($members as $member)
        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
          <article class="card h-100 glass-card border-0 p-3 text-center d-flex flex-column justify-content-between position-relative overflow-hidden transition-hover">
            <div>
              <div class="client-avatar-wrapper rounded-circle overflow-hidden shadow-lg mx-auto mb-4 border border-secondary" style="width: 140px; height: 140px; border-width: 3px !important; border-color: var(--gold) !important; transition: transform 0.3s ease;">
                @if($member->profilePhoto)
                  <img src="{{ image_url($member->profilePhoto) }}" alt="{{ $member->full_name }}" class="w-100 h-100 object-fit-cover">
                @else
                  <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                    <i class="fa-solid fa-user fa-3x"></i>
                  </div>
                @endif
              </div>
              
              <span class="badge bg-dark border border-secondary text-uppercase tracking-wider small mb-2 text-warning" style="font-size: 0.65rem;">
                {{ $member->department?->name ?? 'Specialist' }}
              </span>
              
              <h4 class="h5 fw-bold text-white mb-1 font-serif" style="font-family: 'Playfair Display', serif;">
                <a href="{{ route('team.show', $member) }}" class="text-decoration-none hover-gold">
                  {{ $member->full_name }}
                </a>
              </h4>
              <p class="text-muted small mb-3">{{ $member->designation }}</p>
            </div>
            
            <div class="mt-auto pt-3 border-top border-secondary">
              <a href="{{ route('team.show', $member) }}" class="btn btn-sm btn-outline-light rounded-pill px-4">
                View Profile
              </a>
            </div>
          </article>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="glass-card p-5">
            <i class="fa-solid fa-user-slash fa-3x mb-3 text-muted"></i>
            <h5 class="text-white">No Team Members Found</h5>
            <p class="text-muted mb-0">Try adjusting your filters or search query.</p>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($members->hasPages())
      <div class="d-flex justify-content-center mt-5">
        {{ $members->links('pagination::bootstrap-5') }}
      </div>
    @endif

  </div>
</section>
@endsection

