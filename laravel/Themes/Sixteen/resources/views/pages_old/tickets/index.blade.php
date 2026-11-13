<?php

declare(strict_types=1);

use function Laravel\Folio\name;

name('tickets.list');
?>

<x-layouts.app
    bodyPage="ticket-list"
    :title="__('fixcity::ticket.heading.title.label')"
    :meta-description="__('fixcity::ticket.heading.subtitle.text')"
>
    <div class="page-content content" data-slug="tickets.index" data-side="content">
        <x-page side="content" slug="tickets.index" :data="[]" />
    </div>
</x-layouts.app>
