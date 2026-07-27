<?php

declare(strict_types=1);

use Modules\Cms\Filament\Fields\PageContentBuilder;
use Modules\Cms\Tests\TestCase;

uses(TestCase::class);
test('PageContentBuilder can be instantiated', function () {
    $field = PageContentBuilder::make('content');
});
