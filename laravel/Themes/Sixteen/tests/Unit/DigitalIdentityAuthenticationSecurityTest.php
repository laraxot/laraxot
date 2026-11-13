<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Tests\TestCase;
use Themes\Sixteen\Actions\Auth\SanitizeReturnUrlAction;
use Themes\Sixteen\Actions\CieAuthAction;
use Themes\Sixteen\Actions\SpidAuthAction;

require_once dirname(__DIR__, 2).'/app/Actions/Auth/SanitizeReturnUrlAction.php';
require_once dirname(__DIR__, 2).'/app/Actions/SpidAuthAction.php';
require_once dirname(__DIR__, 2).'/app/Actions/CieAuthAction.php';

uses(TestCase::class);

test('digital identity return urls stay on the current host', function (mixed $candidate, string $expected): void {
    $request = Request::create('https://localhost/it/auth/login');

    expect(app(SanitizeReturnUrlAction::class)->execute($request, $candidate, '/it/area-personale'))
        ->toBe($expected);
})->with([
    ['/it/area-personale', 'https://localhost/it/area-personale'],
    ['//evil.example/path', '/it/area-personale'],
    ['https://evil.example/path', '/it/area-personale'],
    ["/it/area\t-personale", '/it/area-personale'],
]);

test('SPID rejects a response without an XML signature', function (): void {
    $action = (new ReflectionClass(SpidAuthAction::class))->newInstanceWithoutConstructor();
    $method = new ReflectionMethod(SpidAuthAction::class, 'validateSamlResponse');
    $method->setAccessible(true);
    $document = new DOMDocument;
    $document->loadXML('<samlp:Response xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol"><samlp:Status><samlp:StatusCode Value="urn:oasis:names:tc:SAML:2.0:status:Success"/></samlp:Status></samlp:Response>');

    expect(fn (): mixed => $method->invoke($action, $document))
        ->toThrow(Exception::class, 'SPID authentication failed');
});

test('CIE rejects an unsigned JWT before reading claims', function (): void {
    $action = (new ReflectionClass(CieAuthAction::class))->newInstanceWithoutConstructor();
    $method = new ReflectionMethod(CieAuthAction::class, 'validateIdToken');
    $method->setAccessible(true);

    expect(fn (): mixed => $method->invoke($action, 'eyJhbGciOiJub25lIn0.eyJzdWIiOiIxIn0.'))
        ->toThrow(Exception::class, 'JWT signature verification');
});
