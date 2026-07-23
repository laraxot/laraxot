<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Models\AuthenticationLog;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

final class RecentLoginsWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Recent Logins'; // Rendi static la proprietà

    protected int|string|array $columnSpan = 'full';

    /**
     * Define the columns to display in the table.
     */
    public function getTableColumns(): array
    {
        return [
            'user' => TextColumn::make('user'),
            'login_at' => TextColumn::make('login_at'),
            'ip_address' => TextColumn::make('ip_address'),
            'user_agent' => TextColumn::make('user_agent'),
        ];
    }

    /**
     * Optionally configure additional table settings.
     *
     * @return array<string, Action|ActionGroup>
     */
    public function getTableActions(): array
    {
        return [];
    }

    /**
     * Define the query to fetch recent logins.
     *
     * @return Builder<AuthenticationLog>
     */
    protected function getTableQuery(): Builder
    {
        return AuthenticationLog::query()
            ->where('login_successful', true)
            ->orderBy('login_at', 'desc')
            ->limit(10);
    }
}
