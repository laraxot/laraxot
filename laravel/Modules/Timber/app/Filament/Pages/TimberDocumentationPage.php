<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

use function Safe\file_get_contents;

final class TimberDocumentationPage extends Page
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Documentazione Timber';

    protected static ?string $title = 'Documentazione Timber';

    protected static ?string $slug = 'documentation';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.timber.pages.documentation';

    public string $document = 'README.md';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    public function mount(): void
    {
        $requestedDocument = (string) request()->query('document', $this->document);

        if (array_key_exists($requestedDocument, $this->documents())) {
            $this->document = $requestedDocument;
        }
    }

    /**
     * @return array{documents: array<string, string>, document: string, documentTitle: string, documentHtml: HtmlString}
     */
    protected function getViewData(): array
    {
        $documents = $this->documents();
        $path = base_path('docs/timber/'.$this->document);
        $markdown = is_file($path) ? (string) file_get_contents($path) : '# Documento non trovato';

        return [
            'documents' => $documents,
            'document' => $this->document,
            'documentTitle' => $documents[$this->document] ?? $this->document,
            'documentHtml' => new HtmlString(Str::markdown($markdown, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ])),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function documents(): array
    {
        return [
            'README.md' => 'Indice pacchetto',
            '01-visione-e-perimetro.md' => 'Visione e perimetro',
            '02-flussi-operativi.md' => 'Flussi operativi',
            '03-ui-schermate.md' => 'Specifica UI e schermate',
            '04-modello-dati-e-regole.md' => 'Modello dati e regole',
            '05-regole-business.md' => 'Regole business',
            '06-accettazione-e-governance.md' => 'Accettazione e governance',
        ];
    }
}
