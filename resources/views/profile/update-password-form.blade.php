<x-form-section submit="updatePassword">
    <x-slot name="title">
        <span class="text-rose-400 font-extrabold tracking-tight">{{ __('Edit Password') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-slate-400 font-medium">{{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}</span>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <label for="current_password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Password Lama') }}</label>
            <input id="current_password" type="password" class="mt-1 block w-full bg-slate-800/50 border-slate-700 text-slate-100 rounded-xl focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all placeholder-slate-500" wire:model="state.current_password" autocomplete="current-password" placeholder="Masukkan password saat ini" />
            <x-input-error for="current_password" class="mt-2 text-rose-400" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Password Baru') }}</label>
            <input id="password" type="password" class="mt-1 block w-full bg-slate-800/50 border-slate-700 text-slate-100 rounded-xl focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all placeholder-slate-500" wire:model="state.password" autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error for="password" class="mt-2 text-rose-400" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Konfirmasi Password Baru') }}</label>
            <input id="password_confirmation" type="password" class="mt-1 block w-full bg-slate-800/50 border-slate-700 text-slate-100 rounded-xl focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all placeholder-slate-500" wire:model="state.password_confirmation" autocomplete="new-password" placeholder="Ketik ulang password baru" />
            <x-input-error for="password_confirmation" class="mt-2 text-rose-400" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-4 text-emerald-400 font-bold flex items-center gap-2" on="saved">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ __('Berhasil Disimpan.') }}
        </x-action-message>

        <button type="submit" class="inline-flex items-center px-8 py-3 bg-rose-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-rose-500 focus:bg-rose-500 active:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 focus:ring-offset-slate-900 shadow-[0_0_15px_rgba(225,29,72,0.3)] hover:shadow-[0_0_20px_rgba(225,29,72,0.5)] transition-all duration-200">
            {{ __('Perbarui Password') }}
        </button>
    </x-slot>
</x-form-section>