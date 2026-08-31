---
title: 'Custom casts'
module: Xot
type: reference
slug: custom-casts
description: 'php artisan make:cast Address'
tags: [migrato-da-txt, xot]
converted_from: custom_casts.txt
created: 2026-08-24
updated: 2026-08-24
---

php artisan make:cast Address

https://medium.com/@SlyFireFox/laravel-models-3-common-custom-cast-examples-6d0518ecd799

https://dev.to/slyfirefox/laravel-models-3-common-custom-cast-examples-2com


DB::table(‘orders’)
    ->where(‘address->postalCode’, ‘30582–0378’)
    ->get();


$table->json('address')->nullable();
