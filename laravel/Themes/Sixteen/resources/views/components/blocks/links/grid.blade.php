{{--
    Links Grid Block
    Usage: <x-blocks.links.grid :links="$data['links']" :title="$data['title']" />
--}}

@props(['links' => [], 'title' => ''])

<section class="cmp-links-grid mb-8">
    @if($title)
    <h2 class="title-xlarge mb-4">{{ $title }}</h2>
    @endif

    <div class="row g-4">
        @foreach($links as $link)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card-wrapper card-space">
                <div class="card card-bg">
                    <div class="card-body">
                        <h3 class="card-title h5">
                            <a href="{{ $link['url'] }}" class="text-decoration-none">
                                {{ $link['label'] ?? $link['title'] ?? '' }}
                            </a>
                        </h3>
                        @if(isset($link['description']))
                        <p class="card-text text-muted">{{ $link['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
