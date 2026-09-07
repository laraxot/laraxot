<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Modules\Notify\Filament\Resources\ContactResource;
use Modules\Notify\Filament\Resources\ContactResource\Pages\ListContacts;
use Modules\Notify\Filament\Resources\MailTemplateResource;
use Modules\Notify\Filament\Resources\MailTemplateResource\Pages\ListMailTemplates;
use Modules\Notify\Filament\Resources\NotificationResource;
use Modules\Notify\Filament\Resources\NotificationResource\Pages\ListNotifications;
use Modules\Notify\Filament\Resources\NotificationTemplateResource;
use Modules\Notify\Filament\Resources\NotificationTemplateResource\Pages\PreviewNotificationTemplate;
use Modules\Notify\Tests\Fixtures\EditContactTestProxy;
use Modules\Notify\Tests\Fixtures\PreviewMailTemplateTestProxy;
use Modules\Notify\Tests\Fixtures\ViewNotificationTestProxy;
use Modules\Notify\Tests\TestCase;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
=======
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

use function Safe\file_put_contents;
use function Safe\mkdir;

uses(TestCase::class)->group('no-notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

function makeEditContactTestProxy(): EditContactTestProxy
{
    return new EditContactTestProxy;
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

function makeEditContactTestProxy(): EditContactTestProxy
{
    return new EditContactTestProxy();
>>>>>>> a988596b (first)
}

function makePreviewMailTemplateTestProxy(): PreviewMailTemplateTestProxy
{
<<<<<<< HEAD
    return new PreviewMailTemplateTestProxy;
=======
    return new PreviewMailTemplateTestProxy();
>>>>>>> a988596b (first)
}

function makeViewNotificationTestProxy(): ViewNotificationTestProxy
{
<<<<<<< HEAD
    return new ViewNotificationTestProxy;
=======
    return new ViewNotificationTestProxy();
>>>>>>> a988596b (first)
}

function makePreviewNotificationTemplateTestProxy(): PreviewNotificationTemplate
{
<<<<<<< HEAD
    return new class extends PreviewNotificationTemplate {};
}

test('contact resource form schema exposes expected fields', function (): void {
<<<<<<< HEAD
    $schema = TestCase::assertNotifyArray(ContactResource::getFormSchema());
=======
    $schema = XotBasePest::assertArray(ContactResource::getFormSchemaOld());
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    return new class() extends PreviewNotificationTemplate {};
}

<<<<<<< HEAD
test('contact resource form schema exposes expected fields', function (): void {
    $schema = TestCase::assertNotifyArray(ContactResource::getFormSchema());
>>>>>>> a988596b (first)
=======
>>>>>>> 48f28c29 (.)


test('edit contact page exposes delete header action', function (): void {
    $page = makeEditContactTestProxy();
    $actions = XotBasePest::assertArray($page->exposedHeaderActions());

    Assert::assertArrayHasKey('delete', $actions);
    Assert::assertInstanceOf(DeleteAction::class, $actions['delete']);
});

test('list contacts page exposes expected table columns and filters', function (): void {
    $columns = XotBasePest::assertArray(ListContacts::contactTableColumns());
    $filters = XotBasePest::assertArray(ListContacts::contactTableFilters());

    Assert::assertArrayHasKey('id', $columns);
    Assert::assertInstanceOf(TextColumn::class, $columns['id']);
    Assert::assertArrayHasKey('is_read', $columns);
    Assert::assertInstanceOf(IconColumn::class, $columns['is_read']);
    Assert::assertArrayHasKey('active', $filters);
    Assert::assertInstanceOf(Filter::class, $filters['active']);
    Assert::assertArrayHasKey('inactive', $filters);
    Assert::assertInstanceOf(Filter::class, $filters['inactive']);
});

test('list mail templates page exposes expected table columns', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
    $columns = \assertNotifyArray(ListMailTemplates::mailTemplateTableColumns());
=======
    $columns = XotBasePest::assertArray(ListMailTemplates::mailTemplateTableColumns());
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    $columns = \assertNotifyArray(ListMailTemplates::mailTemplateTableColumns());
>>>>>>> a988596b (first)

    Assert::assertArrayHasKey('slug', $columns);
    Assert::assertInstanceOf(TextColumn::class, $columns['slug']);
    Assert::assertArrayHasKey('subject', $columns);
    Assert::assertInstanceOf(TextColumn::class, $columns['subject']);
    Assert::assertArrayHasKey('counter', $columns);
    Assert::assertInstanceOf(TextColumn::class, $columns['counter']);
});

test('preview mail template page title and header actions are configured', function (): void {
    $page = makePreviewMailTemplateTestProxy();
    $actions = $page->exposedHeaderActions();

    $actions = array_values(XotBasePest::assertArray($actions));
    Assert::assertCount(1, $actions);
    Assert::assertInstanceOf(Action::class, $actions[0]);
});

test('list notifications page exposes expected columns and filters', function (): void {
    $columns = XotBasePest::assertArray(ListNotifications::notificationTableColumns());
    $filters = XotBasePest::assertArray(ListNotifications::notificationTableFilters());

    Assert::assertArrayHasKey('id', $columns);
    Assert::assertInstanceOf(TextColumn::class, $columns['id']);
    Assert::assertArrayHasKey('type', $columns);
    Assert::assertInstanceOf(TextColumn::class, $columns['type']);
    Assert::assertArrayHasKey('read', $filters);
    Assert::assertInstanceOf(Filter::class, $filters['read']);
    Assert::assertArrayHasKey('unread', $filters);
    Assert::assertInstanceOf(Filter::class, $filters['unread']);
    Assert::assertArrayHasKey('type', $filters);
    Assert::assertInstanceOf(SelectFilter::class, $filters['type']);
});

test('notification infolist schema exposes its resource fields', function (): void {
    $schema = makeViewNotificationTestProxy()->exposedInfolistSchema();

    foreach (['id', 'type', 'data', 'read_at', 'created_at'] as $field) {
        Assert::assertArrayHasKey($field, $schema);
        Assert::assertInstanceOf(\Filament\Infolists\Components\TextEntry::class, $schema[$field]);
    }
});

<<<<<<< HEAD
test('mail template resource form schema exposes expected components', function (): void {
    // Nessuna fixture da creare: HtmlLayoutPathSelect legge
    // XotData::make()->getMailHtmlLayoutPath(), cioe' Themes/<pub_theme>/resources/mail-layouts,
    // e in questo progetto pub_theme e' 'Zero', che i suoi layout ce li ha gia'.
<<<<<<< HEAD
<<<<<<< HEAD
    $schema = XotBasePest::assertArray(MailTemplateResource::getFormSchema());
=======
    $schema = XotBasePest::assertArray(MailTemplateResource::getFormSchemaOld());
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    $schema = XotBasePest::assertArray(MailTemplateResource::getFormSchema());
>>>>>>> a988596b (first)

    Assert::assertArrayHasKey('mailable_slug_group', $schema);
    Assert::assertInstanceOf(Group::class, $schema['mailable_slug_group']);
    Assert::assertArrayHasKey('subject', $schema);
    Assert::assertInstanceOf(TextInput::class, $schema['subject']);
    Assert::assertArrayHasKey('html_layout_path', $schema);
    Assert::assertArrayHasKey('html_template', $schema);
    Assert::assertInstanceOf(RichEditor::class, $schema['html_template']);
    Assert::assertArrayHasKey('params_display', $schema);
    Assert::assertArrayHasKey('text_template', $schema);
    Assert::assertInstanceOf(Textarea::class, $schema['text_template']);
});

test('notification resource form schema exposes expected components', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
    $schema = TestCase::assertNotifyArray(NotificationResource::getFormSchema());
=======
    $schema = XotBasePest::assertArray(NotificationResource::getFormSchemaOld());
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    $schema = TestCase::assertNotifyArray(NotificationResource::getFormSchema());
>>>>>>> a988596b (first)

    Assert::assertArrayHasKey('type', $schema);
    Assert::assertInstanceOf(TextInput::class, $schema['type']);
    Assert::assertArrayHasKey('data', $schema);
    Assert::assertInstanceOf(Textarea::class, $schema['data']);
    Assert::assertArrayHasKey('read_at', $schema);
    Assert::assertInstanceOf(DateTimePicker::class, $schema['read_at']);
});

test('notification template resource form schema and pages are configured', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
    $schema = TestCase::assertNotifyArray(NotificationTemplateResource::getFormSchema());
    $pages = TestCase::assertNotifyArray(NotificationTemplateResource::getPages());
=======
    $schema = XotBasePest::assertArray(NotificationTemplateResource::getFormSchemaOld());
    $pages = XotBasePest::assertArray(NotificationTemplateResource::getPages());
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    $schema = TestCase::assertNotifyArray(NotificationTemplateResource::getFormSchema());
    $pages = TestCase::assertNotifyArray(NotificationTemplateResource::getPages());
>>>>>>> a988596b (first)

    Assert::assertArrayHasKey('name', $schema);
    Assert::assertInstanceOf(TextInput::class, $schema['name']);
    Assert::assertArrayHasKey('type', $schema);
    Assert::assertInstanceOf(Select::class, $schema['type']);
    Assert::assertArrayHasKey('attachments', $schema);
    Assert::assertInstanceOf(SpatieMediaLibraryFileUpload::class, $schema['attachments']);
    Assert::assertArrayHasKey('preview', $pages);
});
=======

>>>>>>> 48f28c29 (.)

test('preview notification template page exposes title and subheading', function (): void {
    $page = makePreviewNotificationTemplateTestProxy();

    Assert::assertNotSame('', $page->getTitle());
    Assert::assertNotSame('', $page->getSubheading());
});
