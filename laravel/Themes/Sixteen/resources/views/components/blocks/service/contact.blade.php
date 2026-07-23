{{-- Service Contact Component --}}
@props([
    'title' => 'Contatti',
    'office' => '',
    'phone' => '',
    'email' => '',
    'hours' => '',
    'address' => '',
])

<div class="service-contact py-8 bg-light">
    <div class="container">
        <h2 class="title-xxlarge mb-6">{{ $title }}</h2>
        
        <div class="row g-4">
            @if($office)
            <div class="col-12 col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <x-filament::icon icon="heroicon-o-building-office" class="w-6 h-6 text-primary me-3" />
                            <h3 class="h5 mb-0">Ufficio</h3>
                        </div>
                        <p class="text-gray-700 ms-9">{{ $office }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            @if($phone || $email)
            <div class="col-12 col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <x-filament::icon icon="heroicon-o-phone" class="w-6 h-6 text-primary me-3" />
                            <h3 class="h5 mb-0">Recapiti</h3>
                        </div>
                        @if($phone)
                        <p class="text-gray-700 ms-9 mb-2">
                            <strong>Tel:</strong> 
                            <a href="tel:{{ $phone }}" class="text-decoration-none">{{ $phone }}</a>
                        </p>
                        @endif
                        @if($email)
                        <p class="text-gray-700 ms-9">
                            <strong>Email:</strong> 
                            <a href="mailto:{{ $email }}" class="text-decoration-none">{{ $email }}</a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            @if($hours)
            <div class="col-12 col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <x-filament::icon icon="heroicon-o-clock" class="w-6 h-6 text-primary me-3" />
                            <h3 class="h5 mb-0">Orari</h3>
                        </div>
                        <p class="text-gray-700 ms-9">{{ $hours }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            @if($address)
            <div class="col-12 col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <x-filament::icon icon="heroicon-o-map-pin" class="w-6 h-6 text-primary me-3" />
                            <h3 class="h5 mb-0">Indirizzo</h3>
                        </div>
                        <p class="text-gray-700 ms-9">{{ $address }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
