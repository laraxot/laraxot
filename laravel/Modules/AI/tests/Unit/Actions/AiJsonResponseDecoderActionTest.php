<?php

namespace Modules\AI\Tests\Unit\Actions;

use Modules\AI\Actions\AiJsonResponseDecoderAction;
use Modules\AI\Tests\TestCase;

uses(TestCase::class);

describe('AiJsonResponseDecoder Action', function (): void {
    test('_decodes_valid_json_object', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $result = $action->execute('{"key": "value", "number": 42}');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('key', $result);
        $this->assertSame('value', $result['key']);
        $this->assertArrayHasKey('number', $result);
        $this->assertSame(42, $result['number']);
    });

    test('_returns_empty_array_for_empty_string', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $result = $action->execute('');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    });

    test('_returns_empty_array_for_whitespace_only', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $result = $action->execute('   ');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    });

    test('_returns_empty_array_for_invalid_json', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $result = $action->execute('{invalid json}');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    });

    test('_returns_empty_array_for_non_object_json', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $result = $action->execute('[1, 2, 3]');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    });

    test('_filters_out_non_string_keys', function (): void {
        $action = new AiJsonResponseDecoderAction();
        // JSON with numeric keys (0-indexed arrays)
        $result = $action->execute('{"0": "first", "1": "second", "name": "John"}');

        $this->assertIsArray($result);
        // Numeric string keys should be filtered out
        $this->assertArrayNotHasKey('0', $result);
        $this->assertArrayNotHasKey('1', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertSame('John', $result['name']);
    });

    test('_decodes_complex_nested_json', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $json = '{"status": "success", "data": {"id": 123, "items": ["a", "b"]}, "metadata": {"timestamp": "2024-01-01"}}';
        $result = $action->execute($json);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertSame('success', $result['status']);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertIsArray($result['data']);
        $this->assertIsArray($result['metadata']);
    });

    test('_decodes_json_with_special_characters', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $json = '{"message": "Hello, 世界! 🌍", "unicode": "café"}';
        $result = $action->execute($json);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertSame('Hello, 世界! 🌍', $result['message']);
        $this->assertArrayHasKey('unicode', $result);
        $this->assertSame('café', $result['unicode']);
    });

    test('_decodes_json_with_escaped_quotes', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $json = '{"quote": "He said \\"Hello\\""}';
        $result = $action->execute($json);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('quote', $result);
        $this->assertSame('He said "Hello"', $result['quote']);
    });

    test('_handles_json_with_trailing_whitespace', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $json = '{"key": "value"}  ';
        $result = $action->execute($json);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('key', $result);
        $this->assertSame('value', $result['key']);
    });

    test('_handles_json_with_leading_whitespace', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $json = '  {"key": "value"}';
        $result = $action->execute($json);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('key', $result);
        $this->assertSame('value', $result['key']);
    });

    test('_handles_very_long_json', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $longValue = str_repeat('x', 1000);
        $json = '{"longField": "'.$longValue.'"}';
        $result = $action->execute($json);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('longField', $result);
        $this->assertSame($longValue, $result['longField']);
    });

    test('_handles_json_with_null_values', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $json = '{"field": null, "name": "John"}';
        $result = $action->execute($json);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('field', $result);
        $this->assertNull($result['field']);
        $this->assertArrayHasKey('name', $result);
        $this->assertSame('John', $result['name']);
    });

    test('_handles_json_with_boolean_values', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $json = '{"active": true, "disabled": false}';
        $result = $action->execute($json);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('active', $result);
        $this->assertTrue($result['active']);
        $this->assertArrayHasKey('disabled', $result);
        $this->assertFalse($result['disabled']);
    });

    test('_handles_json_with_floats', function (): void {
        $action = new AiJsonResponseDecoderAction();
        $json = '{"price": 19.99, "rate": 0.5}';
        $result = $action->execute($json);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('price', $result);
        $this->assertSame(19.99, $result['price']);
        $this->assertArrayHasKey('rate', $result);
        $this->assertSame(0.5, $result['rate']);
    });
});
