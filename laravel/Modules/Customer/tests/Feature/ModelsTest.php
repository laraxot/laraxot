<?php

declare(strict_types=1);

it('has Customer model', function () {
    $model = new \Modules\Customer\Models\Customer();
    expect($model)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class);
});

it('has CustomerSite model', function () {
    $model = new \Modules\Customer\Models\CustomerSite();
    expect($model)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class);
});

it('has Contact model', function () {
    $model = new \Modules\Customer\Models\Contact();
    expect($model)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class);
});

it('has ContactRole model', function () {
    $model = new \Modules\Customer\Models\ContactRole();
    expect($model)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class);
});

it('has Reseller model', function () {
    $model = new \Modules\Customer\Models\Reseller();
    expect($model)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class);
});
