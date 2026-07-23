<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Configurazione Email AI</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Legge periodicamente inbox e posta inviata della casella aziendale, salva i messaggi nel gestionale e lascia che l'AI proponga azioni su commesse e interventi.
                    </p>
                </div>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700 dark:bg-white/10 dark:text-gray-200">
                    Stato: {{ $this->integrationStatus() }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">Inbox e posta inviata IMAP</h3>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Email aziendale</span>
                            <input type="email" wire:model.defer="businessEmail" placeholder="assistenza@azienda.it" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Host IMAP</span>
                            <input type="text" wire:model.defer="imapHost" placeholder="imap.provider.it" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Porta</span>
                            <input type="number" min="1" max="65535" wire:model.defer="imapPort" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Crittografia</span>
                            <select wire:model.defer="imapEncryption" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5">
                                <option value="ssl">SSL</option>
                                <option value="tls">TLS</option>
                                <option value="none">Nessuna</option>
                            </select>
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Username IMAP</span>
                            <input type="text" wire:model.defer="imapUsername" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Password IMAP / app password</span>
                            <input type="password" wire:model.defer="imapPassword" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Cartella inbox</span>
                            <input type="text" wire:model.defer="imapFolder" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Cartella posta inviata</span>
                            <input type="text" wire:model.defer="imapSentFolder" placeholder="Sent" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                            <span class="mt-1 block text-xs text-gray-500">Su alcuni provider puo chiamarsi Sent, Sent Mail, Inviata o INBOX.Sent.</span>
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Limite email per lettura</span>
                            <input type="number" min="1" max="100" wire:model.defer="pollLimit" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                            <input type="checkbox" wire:model.defer="pollEnabled" class="rounded border-gray-300" />
                            Abilita lettura automatica ogni 5 minuti
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                            <input type="checkbox" wire:model.defer="pollSentEnabled" class="rounded border-gray-300" />
                            Leggi anche la posta inviata/outbox
                        </label>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">Regole AI</h3>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Modello OpenAI</span>
                            <input type="text" wire:model.defer="openAiModel" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Soglia confidenza AI</span>
                            <input type="number" min="0" max="100" wire:model.defer="confidenceThreshold" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Approvatore predefinito</span>
                            <select wire:model.defer="defaultApprovalUserId" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5">
                                <option value="">Primo utente disponibile</option>
                                @foreach ($this->approvalUserOptions() as $userId => $label)
                                    <option value="{{ $userId }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                            <input type="checkbox" wire:model.defer="autoArchiveCertainNotes" class="rounded border-gray-300" />
                            Auto-archivia solo note certe su commessa
                        </label>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">Test e diagnostica</h3>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <button type="button" wire:click="saveSettings" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white">Salva configurazione</button>
                        <button type="button" wire:click="verifyInbox" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 dark:border-white/10 dark:text-gray-200">Verifica inbox</button>
                        <button type="button" wire:click="pollNow" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 dark:border-white/10 dark:text-gray-200">Leggi adesso</button>
                    </div>

                    @if ($lastDiagnostic)
                        <pre class="mt-4 overflow-auto rounded-lg bg-gray-950 p-4 text-xs text-gray-100">{{ json_encode($lastDiagnostic, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">Come legge le email</h3>
                    <ol class="mt-4 list-decimal space-y-2 pl-5 text-sm text-gray-600 dark:text-gray-300">
                        <li>Configura IMAP della casella dedicata al lavoro.</li>
                        <li>Attiva la lettura automatica: il comando <code>email-ai:poll-inbox</code> gira ogni 5 minuti.</li>
                        <li>Le email ricevute non lette vengono salvate e marcate come lette dopo l'import.</li>
                        <li>Le email inviate vengono lette dalla cartella posta inviata e deduplicate tramite Message-ID.</li>
                        <li>L'AI crea solo proposte: serve conferma umana per modificare commesse o interventi.</li>
                    </ol>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">Webhook alternativo</h3>
                    <p class="mt-2 break-all text-sm text-gray-600 dark:text-gray-300">{{ $this->webhookUrl() }}</p>
                    <label class="mt-4 block text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-200">Secret firma webhook</span>
                        <input type="password" wire:model.defer="inboundSecret" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                    </label>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Il webhook resta utile per Mailgun/Postmark/Resend inbound, ma il flusso principale usa la lettura IMAP inbox/outbox.</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">Ultime email</h3>
                    <div class="mt-4 space-y-3 text-sm">
                        @forelse ($this->recentDiagnostics() as $row)
                            <div class="rounded-lg border border-gray-100 p-3 dark:border-white/10">
                                <div class="font-medium text-gray-950 dark:text-white">#{{ $row['id'] }} - {{ $row['status'] }}</div>
                                <div class="text-gray-600 dark:text-gray-300">{{ $row['email'] }} · {{ $row['created_at'] }}</div>
                                <div class="text-gray-600 dark:text-gray-300">{{ $row['subject'] }}</div>
                                @if ($row['error'] !== '')
                                    <div class="mt-1 text-danger-600">{{ $row['error'] }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-600 dark:text-gray-300">Nessuna email ricevuta.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
