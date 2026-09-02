import EasyMDE from 'easymde';

window.MarkDownEditorUtils = {
    initEasyMde(textarea, autoDownloadFontAwesome = true) {
        return new EasyMDE({
            element: textarea,
            hideIcons: [
                'heading',
                'image',
                'preview',
                'side-by-side',
                'fullscreen',
                'guide',
            ],
            autoDownloadFontAwesome: autoDownloadFontAwesome,
            spellChecker: false,
            status: false,
            insertTexts: {
                link: ['[',  '](https://)'],
            },
        });
    },

    getCursorPosition(codeMirror) {
        return codeMirror.getCursor();
    },

    getCurrentWord(codeMirror) {
        const cursor = codeMirror.getCursor();
        const wordRange = codeMirror.findWordAt(cursor);
        const wordStart = wordRange.anchor.ch;
        const wordEnd = wordRange.head.ch;
        return codeMirror.getRange({ line: cursor.line, ch: wordStart - 1 }, { line: cursor.line, ch: wordEnd }).trim();
    },

    shouldAutocomplete(codeMirror) {
        const currentWord = MarkDownEditorUtils.getCurrentWord(codeMirror);

        return currentWord.startsWith('@');
    },

    updateAutocompleteModalCoordinates(cm, modal) {
        const cursor = cm.getCursor();
        const wordRange = cm.findWordAt(cursor);
        const wordStart = wordRange.anchor.ch;

        const coords = cm.cursorCoords({ line: cursor.line, ch: wordStart - 1 });

        modal.style.top = (cm.display.cursorDiv.getBoundingClientRect().top + cm.defaultTextHeight()) + 'px';
        modal.style.left = coords.left + 'px';
    },

    replaceMentions(codeMirror) {
        const regex = /<span\s+data-mention="[^"]*">([^<]*)<\/span>/g;

        const cursor = codeMirror.getSearchCursor(regex);

        while (cursor.findNext()) {
            const cursorValue = codeMirror.getRange(cursor.from(), cursor.to());

            const result = cursorValue.matchAll(regex);
            const groups = [...result][0];

            const span = document.createElement('span');
            span.textContent = groups[1];
            span.style.fontWeight = 'bold';

            codeMirror.markText(
                cursor.from(),
                cursor.to(),
                {
                    replacedWith: span,
                }
            );
        }
    },
}
