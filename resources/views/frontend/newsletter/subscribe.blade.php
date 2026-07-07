@extends('layouts.app')

@section('title', 'Join Our Newsletter | Fancy Decorators')
@section('meta_description', 'Sign up to receive design inspirations, elite construction guides, and updates from Fancy Decorators.')

@section('content')
<section class="newsletter-landing-section py-5 section-bg" style="min-height: 70vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="card border-0 p-5 shadow-lg bg-white rounded-4" data-aos="fade-up">
                    <div class="brand-badge mb-4 d-inline-block">
                        <span class="badge bg-gold text-white px-3 py-2 text-uppercase tracking-wider" style="background-color: var(--button-background, #b19356);">Newsletter</span>
                    </div>
                    
                    <h1 class="h2 font-heading mb-3">Elevate Your Space</h1>
                    <p class="text-muted mb-5 leading-relaxed">
                        Join our community of architects, design enthusiasts, and developers. Get bespoke interior guides, construction logs, and premium trend forecasts delivered once a month.
                    </p>

                    <div class="newsletter-form-wrapper text-start">
                        <x-newsletter-form 
                            layout="standard"
                            title=""
                            subtitle=""
                            :showName="true"
                            :showPhone="true"
                            :showLanguage="true"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
