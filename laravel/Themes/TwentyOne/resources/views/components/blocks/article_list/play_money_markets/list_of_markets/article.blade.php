{{-- Article List Component - Play Money Markets --}}
{{-- Displays a list of articles in a responsive grid layout --}}

<div class="gap-5 sm:columns-2" style="gap: 1rem; counter-reset: grid;">
    @foreach($articles as $article)
        @php
            $article_model = $article;
            $article = $_theme->mapArticle($article);
            
            // Calculate expiration time
            $closedAt = $article->closed_at instanceof \Carbon\Carbon
                ? $article->closed_at
                : \Carbon\Carbon::parse($article->closed_at);
            $now = \Carbon\Carbon::now();
            $diffInHours = $now->diffInHours($closedAt, false);
            
            $scadenza = null;
            
            switch (true) {
                case $diffInHours > 0 && $diffInHours < 24:
                    $scadenza = 'In ' . round($diffInHours) . ' hours';
                    break;
                case $diffInHours > 24:
                    $scadenza = $closedAt->format('M d, Y');
                    break;
                default:
                    $scadenza = null;
            }
            
            // Rating view mapping
            $ratingViewsMapping = [
                'predict::components.blocks.rating.rating_with_image' => 'pub_theme::filament.widgets.ratings-with-image-widget',
                'predict::components.blocks.rating.v1' => 'pub_theme::filament.widgets.ratings-with-image-widget',
                'predict::components.blocks.rating.v2' => 'pub_theme::filament.widgets.ratings-with-image-widget',
                'predict::components.blocks.rating.rating_with_options' => 'pub_theme::filament.widgets.ratings-with-options-widget',
            ];
            
            // Safely get content_blocks for current locale or default to empty array
            $articleRatingsBlock = data_get($article, 'content_blocks.' . app()->getLocale(), []);
            // Get the view from the first 'rating' block or default
            $firstRatingBlock = collect($articleRatingsBlock)->firstWhere('type', 'rating');
            $articleRatingsBlockView = data_get($firstRatingBlock, 'data.view', 'predict::components.blocks.rating.rating_with_options');
        @endphp
        
        <article 
            class="relative overflow-hidden bg-white border border-gray-700 text-gray-200 p-6 rounded-2xl flex flex-col gap-4 mb-5 max-w-xl w-full shadow-lg hover:shadow-2xl transition-all duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-105 hover:rotate-[-1deg] animate-glow"
            style="break-inside: avoid; height: 480px; max-height: 480px;"
        >
            {{-- Expiration Badge --}}
            @if($article->time_left_for_humans != null)
                {{-- <span class="absolute top-4 right-4 bg-amber-500/90 text-gray-900 text-[10px] font-bold uppercase px-3 py-1 rounded-full shadow-md flex items-center gap-1">
                    ⏰ {{ $scadenza ?? 'Expired' }}
                </span> --}}
            @endif
            
            {{-- Title and Date Section --}}
            <div class="flex gap-1 mt-2">
                {{-- Article Image with Animated Gradient --}}
                <div class="relative rounded-xl overflow-hidden mb-4">
                    <a href="{{ route('article.view', ['lang' => $lang, 'slug' => $article->slug]) }}">
                        <img 
                            src="{{ $article->image }}" 
                            alt="{{ $article->title }}" 
                            class="w-full h-20 object-cover transition-transform duration-500 hover:scale-110" 
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent animate-gradient"></div>
                    </a>
                </div>
                
                {{-- Title and Date Info --}}
                <div class="flex flex-col gap-1">
                    <a 
                        href="{{ route('article.view', ['lang' => $lang, 'slug' => $article->slug]) }}" 
                        class="text-xl text-black font-bold hover:text-amber-400 transition-colors"
                    >
                        {{ $article->title }}
                    </a>
                    
                    @if($scadenza)
                        <div class="text-gray-400 text-xs flex items-center gap-1">
                            📅 {{ $scadenza }}
                        </div>
                    @else
                        <div class="text-gray-400 text-xs">Expired</div>
                    @endif
                </div>
            </div>
            
            {{-- Ratings Section --}}
            <div class="mt-2">
                @livewire(\Modules\Predict\Http\Livewire\Widgets\RatingsWidget::class, [
                    'article' => $article_model,
                    'ratings' => $article->ratings,
                    'profile_credits' => $_profile->credits ?? null,
                    'viewType' => $ratingViewsMapping[$articleRatingsBlockView],
                ])
            </div>
            
            {{-- Tags Section --}}
            @if($article->tags->count())
                <div class="flex flex-wrap gap-2 mt-3">
                    @foreach($article->tags as $tag)
                        <a 
                            href="javascript:;" 
                            class="px-3 py-1 text-xs bg-gradient-to-r from-gray-700/50 to-gray-800/30 backdrop-blur-md border border-gray-600 rounded-full hover:bg-amber-500 hover:text-gray-900 transition-colors"
                        >
                            #<span>{{ $tag }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
            
            {{-- Footer Info --}}
            @include('pub_theme::components.blocks.article_list.play_money_markets.list_of_markets.article.footer_info')
            
            {{-- Ratings Done Section (Authenticated Users Only) --}}
            @if(Auth::check())
                <div class="mt-3">
                    <livewire:article.ratings-done 
                        :article_uuid="$article->uuid" 
                        :article_data="$article->toArray()" 
                        wire:key="$article->uuid" 
                    />
                    @livewire(\Modules\Predict\Http\Livewire\Widgets\RatingsDoneWidget::class, [
                        'article_data' => $article->toArray(), 
                        'user_id' => $_user->id
                    ])
                </div>
            @endif
        </article>
    @endforeach
</div>

    