{{-- Steps Horizontal Component --}}
@props([
    'title' => '',
    'steps' => [],
])

<div class="steps-horizontal py-8">
    @if($title)
    <h2 class="title-xxlarge mb-6">{{ $title }}</h2>
    @endif
    
    <div class="row g-4">
        @foreach($steps as $index => $step)
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="step-number mb-3">
                        <span class="badge rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            {{ $step['number'] ?? ($index + 1) }}
                        </span>
                    </div>
                    
                    @if(isset($step['title']))
                    <h3 class="h5 mb-2">{{ $step['title'] }}</h3>
                    @endif
                    
                    @if(isset($step['description']))
                    <p class="text-muted">{{ $step['description'] }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
