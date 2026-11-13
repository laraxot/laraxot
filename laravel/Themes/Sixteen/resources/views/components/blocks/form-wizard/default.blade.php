@props(['data' => []])

<section class="mx-auto w-full max-w-5xl px-5 py-10" aria-labelledby="form-wizard-title" x-data="{ active: (() => { try { const s = new URLSearchParams(window.location.search).get('step') || ''; const n = s.match(/wizard-step/) ? 3 : s.match(/dati-di-segnalazione/) ? 1 : s.match(/riepilogo/) ? 2 : 0; return n } catch { return 0 } })() }">
    <h2 id="form-wizard-title" class="text-3xl font-bold text-[#191919]">{{ $data['heading'] ?? 'Invio segnalazione' }}</h2>
    <p class="mt-2 text-slate-700">{{ $data['steps_label'] ?? 'Passaggi del servizio' }}</p>
    <ol class="mt-8 grid gap-3 md:grid-cols-4" aria-label="{{ $data['steps_label'] ?? 'Passaggi del servizio' }}">
        @foreach ($data['steps'] ?? [] as $step)
            <li>
                <button type="button" class="flex min-h-14 w-full items-center gap-3 rounded border border-slate-300 bg-white p-3 text-left focus:outline-none focus-visible:ring-4 focus-visible:ring-[#007a52]/40" :aria-current="active === {{ $loop->index }} ? 'step' : false" @click="active = {{ $loop->index }}">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#007a52] font-bold text-white">{{ $step['number'] ?? $loop->iteration }}</span>
                    <span><strong class="block text-[#191919]">{{ $step['title'] ?? '' }}</strong><small class="text-slate-600">{{ $step['body'] ?? '' }}</small></span>
                </button>
            </li>
        @endforeach
    </ol>
</section>
