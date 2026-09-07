<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Speech;

use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\preg_split;

/**
 * Turns an assistant's markdown answer into a short, plain, speakable summary
 * for text-to-speech. Structured content (tables, code blocks, lists markers,
 * markdown emphasis) is stripped so the voice never reads out "absurd" things
 * like table pipes; the result is capped to a few sentences to stay concise.
 */
final class BuildSpokenSummaryAction
{
    private const int MAX_SENTENCES = 3;

    private const int MAX_CHARS = 350;

    public function execute(string $markdown): string
    {
        $plain = $this->toPlainText($markdown);
        if ($plain === '') {
            return '';
        }

        return $this->limit($plain);
    }

    private function toPlainText(string $markdown): string
    {
        $lines = preg_split('/\r\n|\r|\n/', $markdown) ?: [];

        $kept = [];
        $inFence = false;

        foreach ($lines as $line) {
            $trimmed = is_string($line) ? trim($line) : '';

            // Toggle fenced code blocks and drop their content.
            if (str_starts_with($trimmed, '```')) {
                $inFence = ! $inFence;

                continue;
            }
            if ($inFence) {
                continue;
            }

            // Drop table rows and separator lines.
            if ($this->isTableLine($trimmed)) {
                continue;
            }

            $kept[] = $this->stripInline($this->stripLinePrefix($trimmed));
        }

        $joined = implode(' ', array_filter($kept, static fn (string $s): bool => $s !== ''));

        return $this->collapseWhitespace($joined);
    }

    private function isTableLine(string $line): bool
    {
        if ($line === '') {
            return false;
        }

        // Separator row such as |---|:--:|
        if (preg_match('/^\|?\s*:?-{2,}/', $line) === 1 && str_contains($line, '-')) {
            return true;
        }

        // A row with at least two pipe-separated cells.
        return substr_count($line, '|') >= 2;
    }

    private function stripLinePrefix(string $line): string
    {
        // Headings, blockquotes, and list markers.
        $result1 = preg_replace('/^#{1,6}\s+/', '', $line);
        $line = is_string($result1) ? $result1 : $line;

        $result2 = preg_replace('/^>\s?/', '', $line);
        $line = is_string($result2) ? $result2 : $line;

        $result3 = preg_replace('/^[-*+]\s+/', '', $line);
        $line = is_string($result3) ? $result3 : $line;

        $result4 = preg_replace('/^\d+\.\s+/', '', $line);
        $line = is_string($result4) ? $result4 : $line;

        return $line;
    }

    private function stripInline(string $text): string
    {
        // Images ![alt](url) -> alt, links [text](url) -> text.
        $stripped = preg_replace('/!?\[([^\]]*)\]\([^)]*\)/', '$1', $text);
        $text = is_string($stripped) ? $stripped : $text;
        // Bold, inline code and strikethrough markers.
        $text = str_replace(['**', '__', '`', '~~'], '', $text);
        // Paired single-char emphasis: *text* or _text_ -> text.
        $emphasisStripped = preg_replace('/(?<!\w)([*_])(?=\S)(.+?)(?<=\S)\1(?!\w)/u', '$2', $text);
        $text = is_string($emphasisStripped) ? $emphasisStripped : $text;

        return trim($text);
    }

    private function collapseWhitespace(string $text): string
    {
        $whitespaceStripped = preg_replace('/\s+/', ' ', $text);
        $text = is_string($whitespaceStripped) ? $whitespaceStripped : $text;

        return trim($text);
    }

    private function limit(string $text): string
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text) ?: [$text];
        $selected = array_slice($sentences, 0, self::MAX_SENTENCES);
        $stringSentences = array_values(array_filter(
            $selected,
            static fn (mixed $sentence): bool => is_string($sentence) && trim($sentence) !== '',
        ));
        $summary = trim(implode(' ', $stringSentences));

        if (mb_strlen($summary) <= self::MAX_CHARS) {
            return $summary;
        }

        $clipped = mb_substr($summary, 0, self::MAX_CHARS);
        $lastSpace = mb_strrpos($clipped, ' ');
        if ($lastSpace !== false && $lastSpace > 0) {
            $clipped = mb_substr($clipped, 0, $lastSpace);
        }

        return rtrim($clipped).'…';
    }
}
