@props(['url' => url()->current(), 'title' => $title ?? 'Condividi'])

@php
    $encodedUrl = urlencode($url);
    $encodedTitle = urlencode($title);
@endphp

{{-- Share Buttons - Social Sharing --}}
<div class="share-buttons flex items-center gap-2 flex-wrap" role="group" aria-label="Condividi sui social">
    <span class="text-sm text-slate-400 mr-2">Condividi:</span>

    {{-- Twitter/X --}}
    <a
        href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedTitle }}"
        target="_blank"
        rel="noopener noreferrer"
        class="share-btn twitter p-2.5 rounded-lg bg-sky-500 hover:bg-sky-600 transition-all duration-200 hover:scale-110"
        aria-label="Condividi su Twitter"
        title="Condividi su Twitter"
    >
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
        </svg>
    </a>

    {{-- Facebook --}}
    <a
        href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="share-btn facebook p-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 transition-all duration-200 hover:scale-110"
        aria-label="Condividi su Facebook"
        title="Condividi su Facebook"
    >
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
        </svg>
    </a>

    {{-- LinkedIn --}}
    <a
        href="https://www.linkedin.com/shareArticle?mini=true&url={{ $encodedUrl }}&title={{ $encodedTitle }}"
        target="_blank"
        rel="noopener noreferrer"
        class="share-btn linkedin p-2.5 rounded-lg bg-blue-700 hover:bg-blue-800 transition-all duration-200 hover:scale-110"
        aria-label="Condividi su LinkedIn"
        title="Condividi su LinkedIn"
    >
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"/>
        </svg>
    </a>

    {{-- Reddit --}}
    <a
        href="https://reddit.com/submit?url={{ $encodedUrl }}&title={{ $encodedTitle }}"
        target="_blank"
        rel="noopener noreferrer"
        class="share-btn reddit p-2.5 rounded-lg bg-orange-600 hover:bg-orange-700 transition-all duration-200 hover:scale-110"
        aria-label="Condividi su Reddit"
        title="Condividi su Reddit"
    >
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .716-.435 1.333-1.01 1.614a3.111 3.111 0 0 1 .042.52c0 2.694-3.13 4.87-7.004 4.87-3.874 0-7.004-2.176-7.004-4.87 0-.183.015-.366.043-.534A1.748 1.748 0 0 1 4.028 12c0-.968.786-1.754 1.754-1.754.463 0 .898.196 1.207.49 1.207-.883 2.878-1.43 4.744-1.487l.885-4.182a.342.342 0 0 1 .14-.197.35.35 0 0 1 .238-.042l2.906.617a1.214 1.214 0 0 1 1.108-.701zM9.25 12C8.561 12 8 12.562 8 13.25c0 .687.561 1.248 1.25 1.248.687 0 1.248-.561 1.248-1.249 0-.688-.561-1.249-1.249-1.249zm5.5 0c-.687 0-1.248.561-1.248 1.25 0 .687.561 1.248 1.249 1.248.688 0 1.249-.561 1.249-1.249 0-.687-.562-1.249-1.25-1.249zm-5.466 3.99a.327.327 0 0 0-.231.094.33.33 0 0 0 0 .463c.842.842 2.484.913 2.961.913.477 0 2.105-.056 2.961-.913a.361.361 0 0 0 .029-.463.33.33 0 0 0-.464 0c-.547.533-1.684.73-2.512.73-.828 0-1.979-.196-2.512-.73a.326.326 0 0 0-.232-.095z"/>
        </svg>
    </a>

    {{-- Telegram --}}
    <a
        href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedTitle }}"
        target="_blank"
        rel="noopener noreferrer"
        class="share-btn telegram p-2.5 rounded-lg bg-sky-600 hover:bg-sky-700 transition-all duration-200 hover:scale-110"
        aria-label="Condividi su Telegram"
        title="Condividi su Telegram"
    >
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
        </svg>
    </a>

    {{-- WhatsApp --}}
    <a
        href="https://api.whatsapp.com/send?text={{ $encodedTitle }}%20{{ $encodedUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="share-btn whatsapp p-2.5 rounded-lg bg-green-600 hover:bg-green-700 transition-all duration-200 hover:scale-110"
        aria-label="Condividi su WhatsApp"
        title="Condividi su WhatsApp"
    >
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
        </svg>
    </a>

    {{-- Copy Link --}}
    <button
        id="copy-link-btn"
        type="button"
        class="share-btn copy p-2.5 rounded-lg bg-slate-700 hover:bg-slate-600 transition-all duration-200 hover:scale-110"
        aria-label="Copia link"
        title="Copia link"
        data-url="{{ $url }}"
    >
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2m-6 12h8a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-8a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2z"/>
        </svg>
    </button>
</div>

{{-- Copy Success Toast --}}
<div
    id="copy-toast"
    class="fixed bottom-4 right-4 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50"
    role="alert"
    aria-live="polite"
>
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="font-medium">Link copiato!</span>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const copyBtn = document.getElementById('copy-link-btn');
    const toast = document.getElementById('copy-toast');

    if (!copyBtn) return;

    copyBtn.addEventListener('click', async () => {
        const url = copyBtn.dataset.url;

        try {
            // Try Web Share API first (mobile)
            if (navigator.share) {
                await navigator.share({
                    title: document.title,
                    url: url
                });
                return;
            }

            // Fallback: Clipboard API
            await navigator.clipboard.writeText(url);

            // Show toast
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 2000);
        } catch (err) {
            console.error('Failed to share:', err);
        }
    });
});
</script>
@endpush
