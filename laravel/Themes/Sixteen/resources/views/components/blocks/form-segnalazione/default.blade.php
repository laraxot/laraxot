@props(['data' => []])

<div class="mx-auto w-full max-w-3xl rounded border border-slate-200 bg-white p-6 shadow-sm" aria-labelledby="segnalazione-form-title">
    <h2 id="segnalazione-form-title" class="text-2xl font-bold text-[#191919]">{{ $data['heading'] ?? 'Segnalazione disservizio' }}</h2>
    <p class="mt-3 text-slate-700">{{ $data['description'] ?? 'Compila i dati richiesti per inviare la segnalazione.' }}</p>
</div>
