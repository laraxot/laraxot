@php
    $largeUploadBaseUrl = url('/energy-broker/large-uploads');
@endphp

<div
    x-data="{
        busy: false,
        progress: 0,
        message: '',
        uploads: [],
        pollTimer: null,
        chunkSize: 5 * 1024 * 1024,
        baseUrl: @js($largeUploadBaseUrl),
        startUrl: @js(route('energy-broker.large-uploads.start')),
        recentUrl: @js(route('energy-broker.large-uploads.recent')),
        csrf: document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content') ?? '',

        init() {
            this.loadRecent();
            this.pollTimer = window.setInterval(() => this.loadRecent(), 5000);
        },

        async upload() {
            const file = this.$refs.file.files[0] ?? null;
            if (!file) {
                this.message = 'Seleziona un file ZIP o PDF.';
                return;
            }

            this.busy = true;
            this.progress = 0;
            this.message = 'Inizializzazione upload...';

            try {
                const start = await this.postJson(this.startUrl, {
                    filename: file.name,
                    total_size: file.size,
                    chunk_size: this.chunkSize,
                    mime_type: file.type,
                });

                const upload = start.upload;
                const totalChunks = Math.ceil(file.size / this.chunkSize);

                for (let index = 0; index < totalChunks; index++) {
                    const form = new FormData();
                    form.append('chunk_index', String(index));
                    form.append('chunk', file.slice(index * this.chunkSize, Math.min(file.size, (index + 1) * this.chunkSize)), `${file.name}.part${index}`);

                    this.message = `Caricamento blocco ${index + 1}/${totalChunks}`;
                    this.progress = Math.max(1, Math.round((index / totalChunks) * 100));
                    await this.uploadChunk(`${this.baseUrl}/${upload.public_id}/chunk`, form, index, totalChunks);
                    this.progress = Math.round(((index + 1) / totalChunks) * 100);
                }

                const completeResponse = await fetch(`${this.baseUrl}/${upload.public_id}/complete`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json'},
                });

                if (!completeResponse.ok) {
                    throw new Error(await this.errorMessage(completeResponse, 'Errore completamento upload.'));
                }

                this.message = 'Upload completato. Import accodato/elaborazione in corso.';
                await this.loadRecent();
            } catch (error) {
                this.message = error instanceof Error ? error.message : 'Upload non riuscito.';
            } finally {
                this.busy = false;
            }
        },

        async loadRecent() {
            const response = await fetch(this.recentUrl, {
                headers: {'Accept': 'application/json'},
            });

            if (response.ok) {
                const data = await response.json();
                this.uploads = data.uploads ?? [];
            }
        },

        statusLabel(status) {
            return {
                uploading: 'caricamento',
                assembling: 'ricomposizione ZIP',
                queued: 'in coda',
                processing: 'elaborazione AI',
                completed: 'completato',
                needs_review: 'da revisionare',
                failed: 'fallito',
            }[status] ?? status;
        },

        async retry(publicId) {
            const response = await fetch(`${this.baseUrl}/${publicId}/retry`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json'},
            });

            if (!response.ok) {
                this.message = await this.errorMessage(response, 'Retry non riuscito.');
            }

            await this.loadRecent();
        },

        async postJson(url, payload) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message ?? data.error ?? 'Richiesta non riuscita.');
            }

            return data;
        },

        uploadChunk(url, form, index, totalChunks) {
            return new Promise((resolve, reject) => {
                const request = new XMLHttpRequest();
                request.open('POST', url);
                request.setRequestHeader('X-CSRF-TOKEN', this.csrf);
                request.setRequestHeader('Accept', 'application/json');
                request.timeout = 10 * 60 * 1000;

                request.upload.onprogress = (event) => {
                    if (!event.lengthComputable) {
                        return;
                    }

                    const chunkProgress = event.loaded / event.total;
                    const overallProgress = ((index + chunkProgress) / totalChunks) * 100;
                    this.progress = Math.max(1, Math.min(99, Math.round(overallProgress)));
                    this.message = `Caricamento blocco ${index + 1}/${totalChunks} (${Math.round(chunkProgress * 100)}%)`;
                };

                request.onload = () => {
                    if (request.status >= 200 && request.status < 300) {
                        resolve();
                        return;
                    }

                    reject(new Error(this.xhrErrorMessage(request, 'Errore upload chunk.')));
                };

                request.onerror = () => reject(new Error('Connessione interrotta durante upload chunk.'));
                request.ontimeout = () => reject(new Error('Timeout durante upload chunk. Riprova con una connessione stabile.'));
                request.send(form);
            });
        },

        xhrErrorMessage(request, fallback) {
            try {
                const data = JSON.parse(request.responseText || '{}');
                return data.message ?? data.error ?? fallback;
            } catch (error) {
                return fallback;
            }
        },

        async errorMessage(response, fallback) {
            try {
                const data = await response.json();
                return data.message ?? data.error ?? fallback;
            } catch (error) {
                return fallback;
            }
        },
    }"
    class="space-y-5"
>
    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-950">
        Questo import è pensato per ZIP/PDF molto grandi: il file viene caricato a blocchi e poi elaborato in background.
        Non chiudere la finestra durante il caricamento dei blocchi.
    </div>

    <div class="space-y-3">
        <input
            type="file"
            x-ref="file"
            accept=".zip,.pdf,application/zip,application/pdf"
            class="block w-full rounded-lg border border-gray-300 text-sm"
        />

        <div class="flex gap-3">
            <button
                type="button"
                x-on:click="upload()"
                x-bind:disabled="busy"
                class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-50"
            >
                Avvia upload grande
            </button>

            <button
                type="button"
                x-on:click="loadRecent()"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold"
            >
                Aggiorna stato
            </button>
        </div>

        <template x-if="message">
            <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700" x-text="message"></div>
        </template>

        <div class="h-3 overflow-hidden rounded-full bg-gray-200">
            <div class="h-3 rounded-full bg-primary-600 transition-all" x-bind:style="`width: ${progress}%`"></div>
        </div>
        <div class="text-xs text-gray-600" x-text="`${progress}%`"></div>
    </div>

    <div class="space-y-3">
        <h3 class="text-sm font-semibold">Ultimi upload grandi</h3>
        <template x-if="uploads.length === 0">
            <div class="text-sm text-gray-500">Nessun upload grande recente.</div>
        </template>
        <template x-for="item in uploads" x-bind:key="item.public_id">
            <div class="rounded-xl border border-gray-200 p-3 text-sm">
                <div class="font-semibold" x-text="item.original_name"></div>
                <div class="mt-1 text-gray-600">
                    Stato: <span x-text="statusLabel(item.status)"></span>
                    · Chunk: <span x-text="`${item.received_chunks}/${item.total_chunks}`"></span>
                </div>
                <template x-if="item.batch">
                    <div class="mt-2 space-y-2 rounded-lg bg-gray-50 p-2 text-xs text-gray-700">
                        <div>
                            Import PDF:
                            <span x-text="`${item.batch.processed_files}/${item.batch.total_files}`"></span>
                            · Stato batch: <span x-text="statusLabel(item.batch.status)"></span>
                        </div>
                        <div class="mt-1 h-2 overflow-hidden rounded-full bg-gray-200">
                            <div class="h-2 rounded-full bg-primary-600" x-bind:style="`width: ${item.batch.progress}%`"></div>
                        </div>
                        <template x-if="item.batch.documents && item.batch.documents.length > 0">
                            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                                <div class="grid grid-cols-12 gap-2 bg-gray-100 px-2 py-1 font-semibold text-gray-700">
                                    <div class="col-span-5">File</div>
                                    <div class="col-span-2">Stato</div>
                                    <div class="col-span-2">Tipo</div>
                                    <div class="col-span-3">Fornitore</div>
                                </div>
                                <template x-for="document in item.batch.documents" x-bind:key="document.source_file_name">
                                    <div class="border-t border-gray-100 px-2 py-1">
                                        <div class="grid grid-cols-12 gap-2">
                                            <div class="col-span-5 truncate" x-text="document.source_file_name"></div>
                                            <div class="col-span-2" x-text="statusLabel(document.analysis_status)"></div>
                                            <div class="col-span-2 truncate" x-text="document.document_type"></div>
                                            <div class="col-span-3 truncate" x-text="document.supplier_name || '-'"></div>
                                        </div>
                                        <template x-if="document.failure_reason">
                                            <div class="mt-1 text-danger-700" x-text="`Errore: ${document.failure_reason}`"></div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
                <template x-if="item.meta && (item.meta.imported !== undefined || item.meta.failed !== undefined || item.meta.skipped !== undefined)">
                    <div class="mt-2 text-xs text-gray-600">
                        Importati: <span x-text="item.meta.imported ?? 0"></span>
                        · Falliti: <span x-text="item.meta.failed ?? 0"></span>
                        · Duplicati saltati: <span x-text="item.meta.skipped ?? 0"></span>
                    </div>
                </template>
                <template x-if="item.meta && item.meta.errors">
                    <div class="mt-2 text-xs text-danger-700" x-text="JSON.stringify(item.meta.errors)"></div>
                </template>
                <template x-if="['failed', 'needs_review'].includes(item.status)">
                    <button
                        type="button"
                        x-on:click="retry(item.public_id)"
                        class="mt-2 rounded-lg border border-gray-300 px-3 py-1 text-xs font-semibold"
                    >
                        Riprova import
                    </button>
                </template>
            </div>
        </template>
    </div>
</div>
