<div class="card__footer d-flex gap-2 flex-wrap align-items-center justify-content-start">
    {{-- <i class="material-symbols-outlined">
      expand_circle_down
    </i>
    <i class="material-symbols-outlined">
        account_box
    </i>
    <span class="mpoint">Chiacchiere</span>

    <i class="material-symbols-outlined">
        schedule
    </i>

    <i class="material-symbols-outlined">
        device_reset
    </i> --}}
    {{-- {{ dddx($article->getTimeLeft()) }} --}}
    <i class="material-symbols-outlined">
        acute
    </i>
    {{ $article->closed_at->toFormattedDateString() }}

    {{-- <i class="material-symbols-outlined">
        monitoring
    </i> --}}


    {{-- <span class="mpoint">123456789</span> --}}
</div>
{{-- <div class="card__footer d-flex gap-2 flex-wrap align-items-center justify-content-start">
    <i class="material-symbols-outlined">
        account_box
    </i>
    {{ $article->closed_at->toFormattedDateString() }}
</div> --}}



