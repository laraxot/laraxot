<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Feature;

use Illuminate\Support\Facades\File;
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

use function Safe\json_decode;

describe('Json Component', function (): void {
    test('_components_json_is_valid_and_contains_expected_components', function (): void {
        $filePath = base_path('Modules/Notify/app/Console/Commands/_components.json');

        Assert::assertTrue(File::exists($filePath), 'Il file _components.json non esiste');

        $content = File::get($filePath);
        $decoded = json_decode($content, true);
        Assert::assertIsArray($decoded);
        Assert::assertCount(2, $decoded, 'Il file _components.json non contiene i 2 componenti attesi');

        $names = [];
        $classes = [];
        foreach ($decoded as $component) {
            Assert::assertIsArray($component);
            Assert::assertArrayHasKey('name', $component, 'Un componente non ha una chiave "name"');
            Assert::assertArrayHasKey('class', $component, 'Un componente non ha una chiave "class"');
            Assert::assertArrayHasKey('ns', $component, 'Un componente non ha una chiave "ns"');
            $names[] = XotBasePest::assertString($component['name']);
            $classes[] = XotBasePest::assertString($component['class']);
        }

        Assert::assertContains('send-mail-command', $names, 'Componente "send-mail-command" non trovato');
        Assert::assertContains('telegram-webhook', $names, 'Componente "telegram-webhook" non trovato');
        Assert::assertContains('SendMailCommand', $classes, 'Classe "SendMailCommand" non trovata');
        Assert::assertContains('TelegramWebhook', $classes, 'Classe "TelegramWebhook" non trovata');
    });
});
