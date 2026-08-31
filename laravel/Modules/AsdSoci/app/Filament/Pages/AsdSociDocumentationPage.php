<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Filament\Pages;

use Illuminate\Support\Str;

use function Safe\file_get_contents;

final class AsdSociDocumentationPage extends AsdSociPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Documentazione ASD';

    protected static ?string $title = 'Documentazione ASD Soci Ramdivision';

    protected static ?string $slug = 'documentation';

    protected static ?int $navigationSort = 6;

    protected static ?string $asdFeature = 'asd.documentation';

    protected string $view = 'asdsoci::filament.pages.documentation';

    public string $document = '01-mockup-app-soci.md';

    public function mount(): void
    {
        $requestedDocument = (string) request()->query('document', $this->document);

        if (array_key_exists($requestedDocument, $this->documents())) {
            $this->document = $requestedDocument;
        }
    }

    /**
     * @return array{
     *     documents: array<string, string>,
     *     document: string,
     *     documentTitle: string,
     *     documentHtml: string
     * }
     */
    protected function getViewData(): array
    {
        $documents = $this->documents();
        // I documenti mostrati appartengono al modulo, non all'applicazione:
        // stanno accanto alla pagina che li rende e la seguono ovunque.
        $path = module_path('AsdSoci', 'docs/ramdivision/'.$this->document);
        $markdown = is_file($path) ? file_get_contents($path) : '# Documento non trovato';

        return [
            'documents' => $documents,
            'document' => $this->document,
            'documentTitle' => $documents[$this->document] ?? $this->document,
            'documentHtml' => Str::markdown($markdown, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function documents(): array
    {
        return [
            '01-mockup-app-soci.md' => 'Mockup funzionale',
            '02-visione-e-perimetro.md' => 'Visione e perimetro',
            '03-flussi-operativi.md' => 'Flussi operativi',
            '04-ui-schermate.md' => 'UI e schermate',
            '05-modello-dati-e-regole.md' => 'Modello dati e regole',
            '06-accettazione-e-governance.md' => 'Accettazione e governance',
        ];
    }
}
