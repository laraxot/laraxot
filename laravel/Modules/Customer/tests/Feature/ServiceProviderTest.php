<?php

declare(strict_types=1);

it('has CustomerServiceProvider', function () {
    $provider = new \Modules\Customer\Providers\CustomerServiceProvider(app());
    expect($provider)->toBeInstanceOf(\Illuminate\Support\ServiceProvider::class);
});
