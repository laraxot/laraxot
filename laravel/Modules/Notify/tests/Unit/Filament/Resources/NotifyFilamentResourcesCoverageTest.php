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
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

function makeEditContactTestProxy(): EditContactTestProxy
{
    return new EditContactTestProxy();
}

function makePreviewMailTemplateTestProxy(): PreviewMailTemplateTestProxy
{
    return new PreviewMailTemplateTestProxy();
}

function makeViewNotificationTestProxy(): ViewNotificationTestProxy
{
    return new ViewNotificationTestProxy();
}

function makePreviewNotificationTemplateTestProxy(): PreviewNotificationTemplate
{
    return new class() extends PreviewNotificationTemplate {};
}



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
    $columns = \assertNotifyArray(ListMailTemplates::mailTemplateTableColumns());

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



test('preview notification template page exposes title and subheading', function (): void {
    $page = makePreviewNotificationTemplateTestProxy();

    Assert::assertNotSame('', $page->getTitle());
    Assert::assertNotSame('', $page->getSubheading());
});
