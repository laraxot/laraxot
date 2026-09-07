<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Feature;

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Notify\Models\MailTemplate;
use PHPUnit\Framework\Assert;

describe('Template Management Business Logic', function (): void {
    test('template management needs model corrections', function (): void {
        // Tests use incorrect model names (EmailTemplate instead of MailTemplate).
        Assert::assertTrue(class_exists(MailTemplate::class));
<<<<<<< HEAD
=======
use Modules\Notify\Tests\TestCase;

uses(TestCase::class)->group('notify-db');

describe('Template Management Business Logic', function (): void {
    test('template management needs model corrections', function (): void {
        /** @var TestCase $this */
        $this->skipTest('Tests use incorrect model names (EmailTemplate instead of MailTemplate)');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    });
});
