<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Feature;

use Modules\Notify\Database\Factories\NotificationTemplateFactory;
use Modules\Notify\Database\Factories\NotificationTemplateVersionFactory;
use Modules\Notify\Models\NotificationTemplate;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
use RuntimeException;

describe('Notification Template Version Business Logic', function (): void {
    test('_can_create_template_version_with_basic_information', function (): void {
<<<<<<< HEAD
=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('notify-db');

describe('Notification Template Version Business Logic', function (): void {
    test('_can_create_template_version_with_basic_information', function (): void {
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        $template = NotificationTemplateFactory::new()->createOne();

        $version = NotificationTemplateVersionFactory::new()->createOne([
            'template_id' => $template->id,
            'subject' => 'Versione 2 - Conferma Appuntamento',
            'body_html' => '<p>Gentile {{patient_name}}</p>',
            'body_text' => 'Gentile {{patient_name}}',
            'channels' => ['mail'],
            'variables' => ['patient_name', 'appointment_date'],
            'conditions' => ['is_confirmed' => true],
            'version' => 2,
<<<<<<< HEAD
<<<<<<< HEAD
            'change_notes' => 'Aggiornamento copy']);
=======
            'change_notes' => 'Aggiornamento copy',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'change_notes' => 'Aggiornamento copy']);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'notification_template_versions', [
            'id' => $version->id,
            'template_id' => $template->id,
            'subject' => 'Versione 2 - Conferma Appuntamento',
<<<<<<< HEAD
<<<<<<< HEAD
            'version' => 2]);
=======
            'version' => 2,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'version' => 2]);
>>>>>>> a988596b (first)

        Assert::assertSame(2, $version->version);
        Assert::assertSame(['mail'], $version->channels);
        Assert::assertSame(['patient_name', 'appointment_date'], $version->variables);
        Assert::assertSame(['is_confirmed' => true], $version->conditions);
    });

    test('_can_manage_template_version_relationships', function (): void {
        $template = NotificationTemplateFactory::new()->createOne();
        $version = NotificationTemplateVersionFactory::new()->createOne([
<<<<<<< HEAD
<<<<<<< HEAD
            'template_id' => $template->id]);
=======
            'template_id' => $template->id,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template_id' => $template->id]);
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(NotificationTemplate::class, $version->template);
        Assert::assertSame($template->id, $version->template->id);
    });

    test('_can_restore_template_from_version', function (): void {
        $template = NotificationTemplateFactory::new()->createOne([
            'subject' => 'Versione Originale',
<<<<<<< HEAD
<<<<<<< HEAD
            'body_html' => '<p>Contenuto originale</p>']);
=======
            'body_html' => '<p>Contenuto originale</p>',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'body_html' => '<p>Contenuto originale</p>']);
>>>>>>> a988596b (first)

        $version = NotificationTemplateVersionFactory::new()->createOne([
            'template_id' => $template->id,
            'subject' => 'Versione Precedente',
            'body_html' => '<p>Contenuto versione precedente</p>',
            'body_text' => 'Contenuto versione precedente',
            'channels' => ['mail'],
            'variables' => ['patient_name'],
            'conditions' => ['is_active' => true],
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'version' => 1]);

        $template->update([
            'subject' => 'Versione Corrente',
            'body_html' => '<p>Contenuto corrente</p>']);
<<<<<<< HEAD
=======
            'version' => 1,
        ]);

        $template->update([
            'subject' => 'Versione Corrente',
            'body_html' => '<p>Contenuto corrente</p>',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        $restoredTemplate = $version->restoreTemplate();

        Assert::assertSame('Versione Precedente', $restoredTemplate->subject);
        Assert::assertSame('<p>Contenuto versione precedente</p>', $restoredTemplate->body_html);
        Assert::assertSame('Contenuto versione precedente', $restoredTemplate->body_text);
        Assert::assertSame(['mail'], $restoredTemplate->channels);
        Assert::assertSame(['patient_name'], $restoredTemplate->variables);
        Assert::assertSame(['is_active' => true], $restoredTemplate->conditions);
    });

    test('_throws_exception_when_restoring_without_template', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
        $version = NotificationTemplateVersionFactory::new()->createOne([
            'template_id' => 999999]);
        expect(fn () => $version->restoreTemplate())->toThrow(RuntimeException::class);
=======
        /** @var TestCase $this */
        $version = NotificationTemplateVersionFactory::new()->createOne([
            'template_id' => 999999,
        ]);
        $this->expectApplicationException(RuntimeException::class);
        $version->restoreTemplate();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $version = NotificationTemplateVersionFactory::new()->createOne([
            'template_id' => 999999]);
        expect(fn () => $version->restoreTemplate())->toThrow(RuntimeException::class);
>>>>>>> a988596b (first)
    });
});
