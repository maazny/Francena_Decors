@php($brands = App\Services\ClientBrandService::getHomepageClientBrands(6))

@if($brands->isNotEmpty())
  <section class="py-5 bg-white">
    <div class="container">
      <div class="section-header text-center mb-5">
        <span class="section-label">Clients & Brands</span>
        <h2>Trusted by leading clients and partner brands</h2>
        <p>We have built lasting relationships with homeowners, businesses, and design-forward brands.</p>
      </div>
      <x-client-brands-grid :brands="$brands" />
    </div>
  </section>
@endif
