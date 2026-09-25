<?php

declare(strict_types=1);

namespace Modules\Lang\Tests\Unit\Actions;

use Modules\Lang\Actions\Translation\RecordMissingTranslationAction;
use Modules\Lang\Models\Translation;
use Modules\Lang\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('RecordMissingTranslationAction', function () {
    test('creates a translation record for a namespaced key', function () {
        $key = 'meetup::messages.welcome';

        app(RecordMissingTranslationAction::class)->execute($key, 'it');

        /** @var Translation|null $record */
        $record = Translation::query()->where('group', 'messages')->where('item', 'welcome')->first();
        Assert::assertNotNull($record);
        Assert::assertSame('it', $record->lang);
        Assert::assertSame('meetup', $record->namespace);
    });

    test('parses a simple key without namespace as wildcard', function () {
        $key = 'simple_key';

        app(RecordMissingTranslationAction::class)->execute($key, 'en');

        /** @var Translation|null $record */
        $record = Translation::query()->where('group', 'simple_key')->whereNull('item')->first();
        Assert::assertNotNull($record);
        Assert::assertSame('*', $record->namespace);
        Assert::assertSame('simple_key', $record->group);
    });

    test('does not duplicate an existing missing key', function () {
        $key = 'dup::auth.login';

        app(RecordMissingTranslationAction::class)->execute($key, 'it');
        app(RecordMissingTranslationAction::class)->execute($key, 'it');

        $count = Translation::query()->where('group', 'auth')->where('item', 'login')->count();
        Assert::assertSame(1, $count);
    });
});
