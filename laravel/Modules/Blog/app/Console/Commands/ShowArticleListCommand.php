<?php

declare(strict_types=1);

namespace Modules\Blog\Console\Commands;

use Illuminate\Console\Command;
use Modules\Blog\Models\Article;

class ShowArticleListCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'blog:article-list';

    /**
     * The console command description.
     */
    protected $description = 'Visualizza lista articoli';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $map = static fn (Article $row) =>
            // $result['price'] = Money::toString($result['price']);
            $row->toArray();

        $rows = Article::all(['id', 'title'])->map($map);

        if (0 === \count($rows)) {
            $this->newLine();
            $this->warn('⚡ No products in the stock');
            $this->newLine();

            return;
        }

        $headers = array_keys($rows[0] ?? []);

        $this->newLine();
        $this->table($headers, $rows);
        $this->newLine();
    }
}
