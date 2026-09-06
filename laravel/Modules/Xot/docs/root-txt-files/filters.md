---
title: 'Filters'
module: Xot
type: reference
slug: filters
description: 'https://www.algolia.com/blog/engineering/implementing-faceted-search-with-dynamic-faceting-with-code/ !!!'
tags: [migrato-da-txt, xot]
<<<<<<< HEAD
converted_from: _filters.txt
=======
converted_from: filters.txt
>>>>>>> 7f6cf6be (.)
created: 2026-08-24
updated: 2026-08-24
---

https://www.algolia.com/blog/engineering/implementing-faceted-search-with-dynamic-faceting-with-code/  !!!


///--------------------------------------------------------------------

http://tucker-eric.github.io/EloquentFilter/
return User::filter($request->all())->get();

///--------------------------------------------------------------------

https://faun.pub/dynamic-filters-with-laravel-eloquent-2dad9d9ff7c2


///--------------------------------------------------------------------

https://orchid.software/en/docs/filters/


https://github.com/pricecurrent/laravel-eloquent-filters


$post = Post::search($query, function ($algolia, $query, $options) use ($category){
    $new_options = [];
    if (!is_null($type)) {
        $new_options = ["facetFilters"=>"category_name:".$category];
    }
    return $algolia->search($query, array_merge($options,$new_options));
});

https://stackoverflow.com/questions/46285500/laravel-scout-search-with-facetfilters

https://docs.meilisearch.com/learn/advanced/filtering_and_faceted_search.html


https://appdividend.com/2022/03/01/how-to-create-filters-in-laravel/  !
