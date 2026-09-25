<?php

declare(strict_types=1);

namespace Modules\Blog\Support;

use Carbon\Carbon;
use Modules\Blog\Models\Article;

final class ArticleTimeLeftFormatter
{
    public function forHumans(Article $article): string
    {
        $endDate = $article->closed_at;
        $startDate = Carbon::now();

        if ($startDate > $endDate) {
            return (string) (__('blog::article.single_expired') ?? '');
        }

        $diff = $startDate->diff($endDate);
        $month = $diff->m;

        if ($month > 0) {
            return $endDate->format('Y-m-d');
        }

        $days = $diff->d;
        $hours = $diff->h;
        $minutes = $diff->i;

        if (0 === $month && 0 === $days && 0 === $hours && 0 === $minutes) {
            return (string) (__('blog::article.single_expired') ?? '');
        }

        if ($days > 0) {
            return (string) (__('blog::article.time_left_days', ['days' => $days]) ?? '');
        }

        return (string) (__('blog::article.time_left', ['hours' => $hours, 'minutes' => $minutes]) ?? '');
    }
}
