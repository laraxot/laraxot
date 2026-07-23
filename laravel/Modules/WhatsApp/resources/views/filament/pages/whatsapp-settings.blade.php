<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Configurazione WhatsApp Business Cloud API</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Collega il numero WhatsApp Business a Meta Cloud API, ricevi i messaggi nel gestionale e lascia che l'AI proponga azioni su commesse e interventi.
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
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">Provider e parametri</h3>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label class="block text-sm md:col-span-2">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Provider WhatsApp</span>
                            <select wire:model.defer="provider" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5">
                                <option value="meta">Meta Cloud API diretta</option>
                                <option value="ycloud">YCloud (consigliato per coesistenza con app telefono)</option>
                            </select>
                            <span class="mt-1 block text-xs text-gray-500">Usa YCloud se vuoi continuare a usare WhatsApp Business dal telefono e inviare i messaggi al gestionale via webhook.</span>
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Phone Number ID</span>
                            <input type="text" wire:model.defer="phoneNumberId" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Business Account ID</span>
                            <input type="text" wire:model.defer="businessAccountId" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Numero WhatsApp aziendale</span>
                            <input type="text" wire:model.defer="businessNumber" placeholder="+39..." class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Versione API</span>
                            <input type="text" wire:model.defer="apiVersion" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm md:col-span-2">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Access token Meta</span>
                            <input type="password" wire:model.defer="accessToken" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                            <span class="mt-1 block text-xs text-gray-500">Preferibile configurarlo via env; se inserito qui viene salvato nelle impostazioni del gestionale.</span>
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">YCloud API key</span>
                            <input type="password" wire:model.defer="ycloudApiKey" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                            <span class="mt-1 block text-xs text-gray-500">La trovi in YCloud Console > Developers > API Keys.</span>
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">YCloud webhook secret</span>
                            <input type="password" wire:model.defer="ycloudWebhookSecret" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                            <span class="mt-1 block text-xs text-gray-500">Opzionale ma consigliato: serve per verificare la firma dei webhook YCloud.</span>
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">App secret</span>
                            <input type="password" wire:model.defer="appSecret" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Verify token webhook</span>
                            <input type="text" wire:model.defer="verifyToken" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
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
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Destinatario test</span>
                            <input type="text" wire:model.defer="testRecipient" placeholder="+39..." class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">Messaggio test</span>
                            <input type="text" wire:model.defer="testMessage" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <button type="button" wire:click="saveSettings" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white">Salva configurazione</button>
                        <button type="button" wire:click="verifyConfiguration" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 dark:border-white/10 dark:text-gray-200">Verifica configurazione</button>
                        <button type="button" wire:click="sendTestMessage" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 dark:border-white/10 dark:text-gray-200">Invia messaggio di test</button>
                    </div>

                    @if ($lastDiagnostic)
                        <pre class="mt-4 overflow-auto rounded-lg bg-gray-950 p-4 text-xs text-gray-100">{{ json_encode($lastDiagnostic, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">Guida rapida Meta</h3>
                    <ol class="mt-4 list-decimal space-y-2 pl-5 text-sm text-gray-600 dark:text-gray-300">
                        <li>Crea o seleziona l'app su Meta Developers.</li>
                        <li>Abilita WhatsApp Business Cloud API.</li>
                        <li>Copia Phone Number ID e Business Account ID.</li>
                        <li>In Meta imposta l'URL webhook: <code>{{ $this->verifyWebhookUrl() }}</code></li>
                        <li>Usa lo stesso verify token configurato qui.</li>
                        <li>Sottoscrivi gli eventi messages.</li>
                        <li>Premi Verifica configurazione e invia un messaggio di test.</li>
                    </ol>
                    <div class="mt-5 rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm dark:border-white/10 dark:bg-white/5">
                        <h4 class="font-semibold text-gray-950 dark:text-white">Link utili</h4>
                        <ul class="mt-3 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <a href="https://developers.facebook.com/apps/" target="_blank" rel="noopener" class="font-medium text-primary-600 underline underline-offset-2 hover:text-primary-500">
                                    Dashboard Meta Developers
                                </a>
                                <span>per creare o selezionare l'app.</span>
                            </li>
                            <li>
                                <a href="https://developers.facebook.com/docs/whatsapp/cloud-api/get-started" target="_blank" rel="noopener" class="font-medium text-primary-600 underline underline-offset-2 hover:text-primary-500">
                                    WhatsApp Cloud API Get Started
                                </a>
                                <span>per il primo setup e il numero di test.</span>
                            </li>
                            <li>
                                <a href="https://developers.facebook.com/docs/whatsapp/cloud-api/guides/set-up-webhooks/" target="_blank" rel="noopener" class="font-medium text-primary-600 underline underline-offset-2 hover:text-primary-500">
                                    Configurazione Webhook
                                </a>
                                <span>per URL webhook, verify token ed eventi.</span>
                            </li>
                            <li>
                                <a href="https://developers.facebook.com/docs/whatsapp/cloud-api/phone-numbers/" target="_blank" rel="noopener" class="font-medium text-primary-600 underline underline-offset-2 hover:text-primary-500">
                                    Business Phone Numbers
                                </a>
                                <span>per Phone Number ID e numero WhatsApp.</span>
                            </li>
                            <li>
                                <a href="https://developers.facebook.com/docs/whatsapp/access-tokens/" target="_blank" rel="noopener" class="font-medium text-primary-600 underline underline-offset-2 hover:text-primary-500">
                                    Access Tokens Guide
                                </a>
                                <span>per token permanenti e permessi API.</span>
                            </li>
                            <li>
                                <a href="https://business.facebook.com/settings/" target="_blank" rel="noopener" class="font-medium text-primary-600 underline underline-offset-2 hover:text-primary-500">
                                    Business Settings
                                </a>
                                <span>per utenti di sistema, asset e token di produzione.</span>
                            </li>
                            <li>
                                <a href="https://helpdocs.ycloud.com/help-center/whatsapp-accounts-management/create-a-whatsapp-api-account/whatsapp-business-app-coexistence" target="_blank" rel="noopener" class="font-medium text-primary-600 underline underline-offset-2 hover:text-primary-500">
                                    YCloud Business App Coexistence
                                </a>
                                <span>per usare lo stesso numero su app WhatsApp Business e API.</span>
                            </li>
                            <li>
                                <a href="https://docs.ycloud.com/reference/webhook-integration-guide" target="_blank" rel="noopener" class="font-medium text-primary-600 underline underline-offset-2 hover:text-primary-500">
                                    YCloud Webhook Integration
                                </a>
                                <span>per configurare il webhook verso iTER7.</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-900 dark:bg-amber-400/10 dark:text-amber-200">
                        I messaggi inviati manualmente dall'app WhatsApp del telefono vengono tracciati solo se passano dalla Cloud API o da un inbox/provider collegato.
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">URL webhook</h3>
                    <p class="mt-2 break-all text-sm text-gray-600 dark:text-gray-300">{{ $this->webhookUrl() }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Se usi YCloud, configura questo URL in YCloud Console > Developers > Webhooks e sottoscrivi l'evento <code>whatsapp.inbound_message.received</code>.</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">Ultimi messaggi</h3>
                    <div class="mt-4 space-y-3 text-sm">
                        @forelse ($this->recentDiagnostics() as $row)
                            <div class="rounded-lg border border-gray-100 p-3 dark:border-white/10">
                                <div class="font-medium text-gray-950 dark:text-white">#{{ $row['id'] }} {{ $row['direction'] }} - {{ $row['status'] }}</div>
                                <div class="text-gray-600 dark:text-gray-300">{{ $row['phone'] }} · {{ $row['created_at'] }}</div>
                                @if ($row['error'] !== '')
                                    <div class="mt-1 text-danger-600">{{ $row['error'] }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-600 dark:text-gray-300">Nessun webhook ricevuto.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
