<?php

namespace Modules\AI\Tests\Unit\Actions;

use Modules\AI\Actions\PredictionDraftFallbackTemplatesAction;
use Modules\AI\Tests\TestCase;

uses(TestCase::class);

describe('PredictionDraftFallbackTemplates Action', function (): void {
    test('_returns_expected_template_categories', function (): void {
        $action = new PredictionDraftFallbackTemplatesAction();
        $result = $action->execute();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $expectedKeys = ['analysis', 'category', 'description', 'options', 'subtitle', 'tags', 'title'];
        $actualKeys = array_keys($result[0]);
        sort($actualKeys);
        $this->assertSame($expectedKeys, $actualKeys);

        // Verify that we have multiple categories
        $categories = array_unique(array_column($result, 'category'));
        $this->assertGreaterThanOrEqual(5, count($categories));

        // Verify required fields for each template
        foreach ($result as $template) {
            $this->assertNotEmpty($template['category']);
            $this->assertNotEmpty($template['title']);
            $this->assertNotEmpty($template['subtitle']);
            $this->assertNotEmpty($template['description']);
            $this->assertNotEmpty($template['analysis']);
            $this->assertIsArray($template['tags']);
            $this->assertIsArray($template['options']);
        }
    });

    test('_has_expected_categories_present', function (): void {
        $action = new PredictionDraftFallbackTemplatesAction();
        $result = $action->execute();

        $categories = array_column($result, 'category');

        $this->assertContains('Sport', $categories);
        $this->assertContains('Crypto', $categories);
        $this->assertContains('Politica', $categories);
        $this->assertContains('Tecnologia', $categories);
        $this->assertContains('Economia', $categories);
    });

    test('_templates_contain_options', function (): void {
        $action = new PredictionDraftFallbackTemplatesAction();
        $result = $action->execute();

        foreach ($result as $template) {
            if ($template['category'] === 'Economia') {
                $this->assertContains('0.25%', $template['options']);
                $this->assertContains('0.50%', $template['options']);
                $this->assertContains('Mantenimento', $template['options']);
                $this->assertContains('Altro', $template['options']);
            } else {
                $this->assertContains('Sì', $template['options']);
                $this->assertContains('No', $template['options']);
            }
        }
    });

    test('_templates_have_tags', function (): void {
        $action = new PredictionDraftFallbackTemplatesAction();
        $result = $action->execute();

        foreach ($result as $template) {
            $this->assertNotEmpty($template['tags']);
            $this->assertIsArray($template['tags']);
            $this->assertNotEmpty(current($template['tags']));
        }
    });

    test('_template_structure_matches_expectation', function (): void {
        $action = new PredictionDraftFallbackTemplatesAction();
        $result = $action->execute();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        // Verify the first template has all expected structure
        $firstTemplate = $result[0];
        $expectedKeys = ['category', 'title', 'subtitle', 'description', 'analysis', 'tags', 'options'];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $firstTemplate);
        }

        // Verify data types
        $this->assertIsString($firstTemplate['category']);
        $this->assertIsString($firstTemplate['title']);
        $this->assertIsString($firstTemplate['subtitle']);
        $this->assertIsString($firstTemplate['description']);
        $this->assertIsString($firstTemplate['analysis']);
        $this->assertIsArray($firstTemplate['tags']);
        $this->assertIsArray($firstTemplate['options']);
    });
});
