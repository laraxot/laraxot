<<<<<<<< HEAD:laravel/Modules/Xot/docs/_archive/root-txt-files/custom_casts.md
---
title: "Custom casts"
type: reference
status: active
created: 2026-08-27
updated: 2026-08-27
note: "Convertito da custom_casts.txt (documento) da convert-docs-txt-to-md.py."
---

# Custom casts

========
>>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php):laravel/Modules/Xot/docs/_archive/custom-casts.md

php artisan make:cast Address

https://medium.com/@SlyFireFox/laravel-models-3-common-custom-cast-examples-6d0518ecd799

https://dev.to/slyfirefox/laravel-models-3-common-custom-cast-examples-2com




DB::table(‘orders’)
    ->where(‘address->postalCode’, ‘30582–0378’)
    ->get();


<<<<<<<< HEAD:laravel/Modules/Xot/docs/_archive/root-txt-files/custom_casts.md
$table->json('address')->nullable();
========
$table->json('address')->nullable();
>>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php):laravel/Modules/Xot/docs/_archive/custom-casts.md
