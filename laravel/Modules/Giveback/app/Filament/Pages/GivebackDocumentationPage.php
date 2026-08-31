<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

use Modules\Giveback\Support\GenerateGivebackMockupPdfAction;
use Filament\Actions\Action;
use Illuminate\Support\Str;

use function Safe\file_get_contents;

final class GivebackDocumentationPage extends GivebackPlatformPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Documentazione GiveBack';

    protected static ?string $title = 'Documentazione GiveBack';

    protected static ?string $slug = 'documentation';

    protected static ?int $navigationSort = 9;

    protected string $view = 'giveback::filament.pages.documentation';

    public string $document = '01-mockup-giveback-platform.md';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadMockupPdf')
                ->label('Scarica PDF mockup')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    return response()->streamDownload(
                        function (): void {
                            echo app(GenerateGivebackMockupPdfAction::class)->execute();
                        },
                        'giveback-platform-mockup.pdf'
                    );
                }),
        ];
    }

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
        // Vedi AsdSociDocumentationPage: i documenti sono asset del modulo.
        $path = module_path('Giveback', 'docs/platform/'.$this->document);
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
            '01-mockup-giveback-platform.md' => 'Mockup funzionale',
            '00-allegato-1-trascrizione.md' => 'Trascrizione allegato 1',
            '02-preventivo-giveback-platform.md' => 'Preventivo GiveBack Platform',
            '03-bandi-consulenti-prossime-azioni.md' => 'Bandi, consulenti e prossime azioni',
            '04-accesso-giulia-pannello-giveback.md' => 'Accesso Giulia al pannello GiveBack',
        ];
    }
}
