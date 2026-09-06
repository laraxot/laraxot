---
title: "Custom casts"
type: reference
status: active
created: 2026-08-27
updated: 2026-08-27
note: "Convertito da custom_casts.txt (documento) da convert-docs-txt-to-md.py."
---

# Custom casts


php artisan make:cast Address

https://medium.com/@SlyFireFox/laravel-models-3-common-custom-cast-examples-6d0518ecd799

https://dev.to/slyfirefox/laravel-models-3-common-custom-cast-examples-2com




DB::table(‘orders’)
    ->where(‘address->postalCode’, ‘30582–0378’)
    ->get();


$table->json('address')->nullable();
