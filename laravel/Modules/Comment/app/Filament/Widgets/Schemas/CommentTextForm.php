<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Widgets\Schemas;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Component;
use Livewire\Component as LivewireComponent;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;
use Modules\Xot\Actions\Cast\SafeStringCastAction;

/**
 * Campo di testo dei commenti del front office (CMT-022): nuovo commento, risposta al singolo commento e modifica.
 *
 * Il widget orchestra, qui vivono campo e regole: `$this->form->getState()` torna il Markdown gia' validato.
 * Etichetta, segnaposto e aiuto arrivano da `comment::comment_text_form.fields.text.*` (traduzione automatica del progetto).
 *
 * Perche' un MarkdownEditor e non un RichEditor: il modello salva `original_text` (sorgente) e `text` (HTML prodotto dalla
 * pipeline di salvataggio, che poi lo sanifica). Il Markdown e' gia' quel sorgente; un editor HTML cambierebbe la pipeline.
 * Barra ridotta di proposito: niente allegati (nessun disco di upload per i commenti), tabelle o blocchi di codice.
 */
class CommentTextForm extends XotBaseResourceForm
{
    /** Lunghezza massima del testo di un commento, in caratteri. */
    public const int MAX_LENGTH = 2000;

    /** Lunghezza minima: un solo carattere non e' un commento. */
    public const int MIN_LENGTH = 2;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return self::getCommentFormSchema();
    }

    /**
     * Schema usato dai widget `CommentsWidget` (nuovo commento) e `CommentWidget` (risposta e modifica).
     *
     * @return array<string, Component>
     */
    public static function getCommentFormSchema(): array
    {
        return [
            'text' => MarkdownEditor::make('text')
                // Lo state path e' sempre `data.text`: senza un id per istanza, nuovo commento, risposte e modifica metterebbero
                // lo stesso id sulla pagina e l'etichetta (`for`) punterebbe al primo editor, non a quello che si sta usando.
                ->id(static fn (LivewireComponent $livewire): string => 'cmt-text-'.SafeStringCastAction::cast($livewire->getId()))
                ->required()
                ->minLength(self::MIN_LENGTH)
                ->maxLength(self::MAX_LENGTH)
                ->toolbarButtons([
                    ['bold', 'italic', 'link'],
                    ['bulletList', 'orderedList', 'blockquote'],
                    ['undo', 'redo'],
                ])
                ->minHeight('7rem')
                ->maxHeight('18rem')
                // Sincronizza mentre si scrive solo se le menzioni sono attive: `CommentsWidget::updatedDataText()` cerca li' la query dopo `@`.
                ->live(debounce: 500, condition: static fn (): bool => (bool) (CommentConfigData::make()->mentions['enabled'] ?? false)),
        ];
    }
}
