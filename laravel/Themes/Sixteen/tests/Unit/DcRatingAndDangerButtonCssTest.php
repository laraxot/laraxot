<?php

declare(strict_types=1);

use function Safe\file_get_contents;

/*
 * DCP-377 (A-02, D-16, T-03). Nelle pagine `data-dc` il CSS servito non aveva `.btn-danger` (il pulsante «Elimina il mio account» era
 * trasparente con testo blu) ne' le regole `.rating` di Bootstrap Italia: le uniche erano daisyUI, spente dentro `data-dc`, e le label
 * delle stelle misuravano 2x0 px. Valori copiati dal CSS ufficiale bootstrap-italia 2.13.0. Solo filesystem.
 */
test('the data-dc components sheet styles the danger button like Design Comuni', function (): void {
    $css = file_get_contents(dirname(__DIR__, 2).'/resources/css/app/20-dc-components.css');

    expect($css)
        ->toContain('body:has(.page-content[data-dc]) .btn.btn-danger {')
        ->toContain('background-color: #cc334d;')
        ->toContain('hsl(350 60% 40.5%)')
        ->toContain('body:has(.page-content[data-dc]) .btn.btn-danger:hover');
});

test('the data-dc components sheet carries the Bootstrap Italia rating rules', function (): void {
    $css = file_get_contents(dirname(__DIR__, 2).'/resources/css/app/20-dc-components.css');

    expect($css)
        ->toContain('.page-content[data-dc] .rating > input {')
        ->toContain('.page-content[data-dc] .rating > label svg {')
        ->toContain('.page-content[data-dc] .rating > input:checked ~ label svg')
        ->toContain('.page-content[data-dc] .rating > input:focus-visible + label svg');
});

test('every star label of the rating prompt holds a star icon', function (): void {
    $view = file_get_contents(dirname(__DIR__, 4).'/Modules/Fixcity/resources/views/filament/widgets/ticket-citizen-rating-prompt.blade.php');

    expect($view)->toContain('#it-star-full');
});
