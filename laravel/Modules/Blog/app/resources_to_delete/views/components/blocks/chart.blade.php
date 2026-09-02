<div>
    {{-- {{ dddx([
        get_defined_vars(),
        $block['data']['chart_type']
        ]) }} --}}


    @include('blog::components.blocks.chart.'.$block['data']['chart_type'])


    {{-- <livewire:article.chart :data="$block['data']" :model="$model" /> --}}
</div>
