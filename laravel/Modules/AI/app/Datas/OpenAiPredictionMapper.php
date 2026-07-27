<?php

declare(strict_types=1);

namespace Modules\AI\Datas;

use Modules\AI\Actions\Cast\CastScalarToNullableStringAction;
use Modules\AI\Actions\Cast\CastScalarToStringAction;
use Modules\AI\Actions\Cast\CastScalarToStringListAction;

class OpenAiPredictionMapper
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function toPredictionData(array $data): PredictionData
    {
        $castString = app(CastScalarToStringAction::class);
        $castNullable = app(CastScalarToNullableStringAction::class);
        $castList = app(CastScalarToStringListAction::class);

        return PredictionData::from([
            'title' => $castString->execute($data['title'] ?? ''),
            'description' => $castString->execute($data['description'] ?? ''),
            'content' => $castString->execute($data['content'] ?? ''),
            'excerpt' => $castString->execute($data['excerpt'] ?? $data['description'] ?? ''),
            'category' => $castString->execute($data['category'] ?? 'Generico'),
            'tags' => $castList->execute($data['tags'] ?? []),
            'closedAt' => $castString->execute($data['closed_at'] ?? now()->addDays(30)->format('Y-m-d')),
            'endsAt' => $castString->execute($data['ends_at'] ?? now()->addDays(60)->format('Y-m-d')),
            'liquidityParameter' => is_numeric($data['liquidity_parameter'] ?? null) ? (float) $data['liquidity_parameter'] : 0.5,
            'stocksCount' => is_numeric($data['stocks_count'] ?? null) ? (int) $data['stocks_count'] : 1000,
            'isWagerable' => is_bool($data['is_wagerable'] ?? null) ? $data['is_wagerable'] : true,
            'contentBlock' => $castNullable->execute($data['content_block'] ?? null),
            'sidebarBlock' => $castNullable->execute($data['sidebar_block'] ?? null),
            'footerBlock' => $castNullable->execute($data['footer_block'] ?? null),
        ]);
    }
}
