<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Feature;

use Modules\Notify\Database\Factories\MailTemplateFactory;
use Modules\Notify\Database\Factories\MailTemplateVersionFactory;
use Modules\Notify\Models\MailTemplate;
use Modules\Notify\Models\MailTemplateVersion;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
>>>>>>> a988596b (first)

describe('Mail Template Version Business Logic', function (): void {
    test('_can_create_mail_template_version_with_basic_information', function (): void {
        $template = MailTemplateFactory::new()->createOne();

        $version = MailTemplateVersionFactory::new()->createOne([
            'template_id' => $template->id,
            'subject' => 'Conferma Appuntamento - Versione 2',
            'html_template' => '<p>Gentile {{patient_name}}</p>',
            'text_template' => 'Gentile {{patient_name}}',
            'version' => 2,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'change_notes' => 'Aggiornamento copy']);
        XotBasePest::assertTableHas('notify', 'mail_template_versions', [
            'id' => $version->id,
            'subject' => 'Conferma Appuntamento - Versione 2',
            'version' => 2]);
<<<<<<< HEAD
=======
            'change_notes' => 'Aggiornamento copy',
        ]);
        XotBasePest::assertTableHas('notify', 'mail_template_versions', [
            'id' => $version->id,
            'subject' => 'Conferma Appuntamento - Versione 2',
            'version' => 2,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        Assert::assertSame(2, $version->version);
        Assert::assertStringContainsString('{{patient_name}}', $version->html_template);
        Assert::assertSame('Aggiornamento copy', $version->change_notes);
    });

    test('_can_manage_mail_template_version_relationships', function (): void {
        $template = MailTemplateFactory::new()->createOne();
        $version = MailTemplateVersionFactory::new()->createOne([
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

        Assert::assertInstanceOf(MailTemplate::class, $version->template);
        Assert::assertSame($template->id, $version->template->id);
    });

    test('_can_store_metadata_on_mail_template_version', function (): void {
        $template = MailTemplateFactory::new()->createOne();
        $metadata = [
            'author' => 'admin@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'review_status' => 'approved'];
=======
            'review_status' => 'approved',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'review_status' => 'approved'];
>>>>>>> a988596b (first)

        $version = MailTemplateVersionFactory::new()->createOne([
            'template_id' => $template->id,
            'metadata' => $metadata,
            'version' => 1,
<<<<<<< HEAD
<<<<<<< HEAD
            'html_template' => '<p>v1</p>']);
=======
            'html_template' => '<p>v1</p>',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'html_template' => '<p>v1</p>']);
>>>>>>> a988596b (first)

        $fresh = $version->fresh();
        Assert::assertInstanceOf(MailTemplateVersion::class, $fresh);
        Assert::assertSame('approved', XotBasePest::assertArray($fresh->metadata)['review_status']);
    });

    test('_can_update_template_content_from_version_fields', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'subject' => 'Versione Corrente',
            'html_template' => '<p>Template corrente</p>',
<<<<<<< HEAD
<<<<<<< HEAD
            'text_template' => 'Template corrente']);
=======
            'text_template' => 'Template corrente',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'text_template' => 'Template corrente']);
>>>>>>> a988596b (first)

        $version = MailTemplateVersionFactory::new()->createOne([
            'template_id' => $template->id,
            'subject' => 'Versione Precedente',
            'html_template' => '<p>Template versione precedente</p>',
            'text_template' => 'Template versione precedente',
<<<<<<< HEAD
<<<<<<< HEAD
            'version' => 1]);
=======
            'version' => 1,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'version' => 1]);
>>>>>>> a988596b (first)

        $template->update([
            'subject' => $version->subject,
            'html_template' => $version->html_template,
<<<<<<< HEAD
<<<<<<< HEAD
            'text_template' => $version->text_template]);
=======
            'text_template' => $version->text_template,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'text_template' => $version->text_template]);
>>>>>>> a988596b (first)

        $freshTemplate = XotBasePest::assertFreshModel($template, MailTemplate::class);
        Assert::assertInstanceOf(MailTemplate::class, $freshTemplate);
        Assert::assertSame('Versione Precedente', $freshTemplate->subject);
        Assert::assertSame('<p>Template versione precedente</p>', $freshTemplate->html_template);
    });
});
