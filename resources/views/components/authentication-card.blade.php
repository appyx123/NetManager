<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
    @if (isset($logo) && trim((string)$logo) !== '')
        <div class="mb-6">
            {{ $logo }}
        </div>
    @endif

    <div class="w-full sm:max-w-md mt-6 px-6 sm:px-8 py-8 bg-slate-900/95 sm:bg-slate-900/85 backdrop-blur-md sm:backdrop-blur-xl border border-slate-800/80 hover:border-amber-500/40 shadow-2xl shadow-black/80 hover:shadow-amber-500/10 transition-all duration-300 overflow-hidden rounded-2xl fade-in-up">
        {{ $slot }}
    </div>
</div>
