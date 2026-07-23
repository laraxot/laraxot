{{-- Vista per il widget PredictTableWidget nel tema TwentyOne --}}
{{-- Mostra i predict in formato card con informazioni complete e ratings --}}
{{-- Versione refactorizzata seguendo i principi DRY e KISS --}}
<?php
    $record = $getRecord();
  
?>
<div class="p-2 space-y-3">
    {{-- Header con stato e badge --}}
    <header class="flex items-center justify-between">
        <x-predict.status-badge 
            :status="$record->status" 
            :with-animation="true" 
        />
        <x-predict.bettable-badge 
            :is-bettable="$record->is_bettable" 
        />
    </header>

    {{-- Contenuto principale --}}
    <main class="space-y-3">
        {{-- Titolo e categoria --}}
        <section>
        
            <a href="{{ route('predict.view', ['lang' => app()->getLocale(), 'slug' => $record->slug]) }}" 
               class="block group">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    {{ $record->title }}
                </h3>
            </a>
            
            @if($record->category)
                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6 2C3.79086 2 2 3.79086 2 6V7C2 9.20914 3.79086 11 6 11H7C9.20914 11 11 9.20914 11 7V6C11 3.79086 9.20914 2 7 2H6ZM17 2C14.7909 2 13 3.79086 13 6V7C13 9.20914 14.7909 11 17 11H18C20.2091 11 22 9.20914 22 7V6C22 3.79086 20.2091 2 18 2H17ZM6 13C3.79086 13 2 14.7909 2 17V18C2 20.2091 3.79086 22 6 22H7C9.20914 22 11 20.2091 11 18V17C11 14.7909 9.20914 13 7 13H6ZM17 13C14.7909 13 13 14.7909 13 17V18C13 20.2091 14.7909 22 17 22H18C20.2091 22 22 20.2091 22 18V17C22 14.7909 20.2091 13 18 13H17Z" />
                    </svg>
                    {{ $record->getBloodlineCategories() }}
                </div>
            @endif
        </section>

        {{-- Ratings section --}}
        @if($record->ratings && $record->ratings->count() > 0)
            <section class="space-y-2">
                {{-- Grid responsiva dei rating --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-1">
                    @foreach($record->ratings as $rating)
                        <x-predict.rating-card 
                            :rating="$rating" 
                            :predict-id="$record->id" 
                        />
                    @endforeach
                </div>

                {{-- Statistiche --}}
                <x-predict.statistics-row 
                    :bets-count="$record->count_credit"
                    :total-credits="$record->sum_credit"
                />
            </section>
        @endif

        {{-- Meta informazioni --}}
        <x-predict.meta-info 
            :predict="$record" 
            :show-author="true"
            :show-date="true"
            :show-expiration="false"
        />
    </main>

    {{-- Footer con azioni --}}
    <footer class="flex items-center justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
        <x-predict.action-buttons 
            :predict="$record" 
            :show-bet-button="false"
        />
        
        <x-predict.meta-info 
            :predict="$record" 
            :show-author="false"
            :show-date="false"
            :show-expiration="true"
        />
    </footer>

    {{-- Modal per le azioni Filament --}}
    <x-filament-actions::modals />
</div>
