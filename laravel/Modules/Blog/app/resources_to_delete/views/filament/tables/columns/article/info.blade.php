<div>
    <div>
        is_featured {{ $getRecord()->is_featured }}
    </div>
    <div>
        {{ $getRecord()->title }}
    </div>
    <div>
        {{ $getRecord()->category->title }}
    </div>
</div>