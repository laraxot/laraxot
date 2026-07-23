<x-layouts.app>
    @php
        $particlesColor = 'rgba(255,255,255,0.28)';
    @endphp
    <div class="antigravity-field relative min-h-screen overflow-hidden bg-slate-950 text-white flex items-center justify-center"
        data-antigravity-field>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.24),_transparent_30%),radial-gradient(circle_at_80%_30%,_rgba(99,102,241,0.22),_transparent_26%),linear-gradient(180deg,_#020617_0%,_#0f172a_55%,_#111827_100%)]"
            aria-hidden="true"></div>

        <x-ui.particles count="72" :color="$particlesColor" size="3px" zIndex="0" />

        <div class="antigravity-grid" aria-hidden="true"></div>
        <div class="antigravity-spotlight" aria-hidden="true"></div>
        <div class="antigravity-orb antigravity-orb-1" aria-hidden="true"></div>
        <div class="antigravity-orb antigravity-orb-2" aria-hidden="true"></div>
        <div class="antigravity-orb antigravity-orb-3" aria-hidden="true"></div>

        <main class="relative z-10 w-full max-w-md px-4 py-14">
            <div class="card-kinetic rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-md shadow">
                <h2 class="text-2xl font-bold text-white">{{ __('user::auth.logout_success') }}</h2>

                <a href="{{ route('home') }}"
                    class="mt-6 inline-flex items-center text-sky-200 underline decoration-sky-200/40 hover:decoration-sky-200">
                    {{ __('user::auth.back_to_home') }}
                </a>

                <div class="mt-8">
                    <form wire:submit="logout">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center rounded-xl bg-white/10 px-4 py-3 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/15 hover:ring-white/30 transition-all duration-200">
                            {{ __('user::auth.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</x-layouts.app>
