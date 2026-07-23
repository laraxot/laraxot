@props([
    'title' => '',
    'topics' => [],
])

@if(!empty($topics))
    <section class="topics-grid py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if($title)
                        <h2 class="mb-4">{{ $title }}</h2>
                    @endif
                </div>
            </div>
            <div class="row">
                @foreach($topics as $topic)
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card card-teaser rounded shadow-sm p-3 h-100">
                            <div class="card-body">
                                @if(!empty($topic['title']))
                                    <h3 class="card-title text-primary font-weight-semibold">
                                        @if(!empty($topic['url']))
                                            <a href="{{ $topic['url'] }}" class="text-decoration-none">
                                                {{ $topic['title'] }}
                                            </a>
                                        @else
                                            {{ $topic['title'] }}
                                        @endif
                                    </h3>
                                @endif
                                @if(!empty($topic['description']))
                                    <p class="card-text text-muted small">
                                        {{ $topic['description'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
