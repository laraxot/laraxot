{{-- Service Details Component --}}
@props([
    'title' => 'Informazioni sul servizio',
    'sections' => [],
])

<div class="service-details py-8">
    <h2 class="title-xxlarge mb-6">{{ $title }}</h2>
    
    <div class="row g-4">
        @foreach($sections as $section)
        <div class="col-12 col-md-6">
            <div class="card card-bg shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 mb-3">{{ $section['title'] }}</h3>
                    <p class="text-gray-700">{{ $section['content'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
