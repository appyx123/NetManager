<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
    @if (isset($logo) && trim((string)$logo) !== '')
        <div class="mb-6">
            {{ $logo }}
        </div>
    @endif

    <div class="w-full sm:max-w-md mt-6 px-6 sm:px-8 py-8 bg-gray-900/40 backdrop-blur-md border border-yellow-500/10 shadow-2xl shadow-yellow-500/10 overflow-hidden rounded-2xl">
        {{ $slot }}
    </div>
</div>
