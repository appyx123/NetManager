<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="fade-in">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-sky-500/10 rounded-lg border border-sky-500/20">
                            <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black text-sky-500 uppercase tracking-[0.2em]">Sales Pipeline</span>
                    </div>
                    <h1 class="text-4xl font-black text-white tracking-tighter">Daftar <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-400">Prospek</span></h1>
                    <p class="text-slate-400 mt-2 font-medium">Kelola data calon pelanggan dan pantau siklus konversi penjualan Anda.</p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('marketing.leads.create') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-sky-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-[0_0_20px_rgba(2,132,199,0.3)] hover:bg-sky-500 hover:shadow-[0_0_30px_rgba(2,132,199,0.5)] transition-all duration-300 transform hover:-translate-y-1 w-full md:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Prospek Baru
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-10 p-5 bg-emerald-500/10 border border-emerald-500/20 rounded-[2rem] flex items-center gap-4 animate-fade-in shadow-lg">
                    <div class="p-2.5 bg-emerald-500 rounded-xl text-white shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-emerald-400 font-bold tracking-wide">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('generated_credential'))
                @php
                    $cred = session('generated_credential');
                    $rawPhone = $cred['phone'] ?? '';
                    $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                    $waText = urlencode("Halo {$cred['name']},\n\nTerima kasih telah berlangganan internet NetManager!\nBerikut adalah akun akses Client Portal Anda:\n\n*ID Pelanggan:* {$cred['code']}\n*Email:* {$cred['username']}\n*Password:* {$cred['password']}\n*Login Portal:* " . url('/login') . "\n\nSaat ini teknisi kami sedang mempersiapkan instalasi ke lokasi Anda. Anda dapat login ke portal terlebih dahulu untuk memantau status pesanan.\n\nTerima kasih!");
                    $waUrl = "https://wa.me/{$cleanPhone}?text={$waText}";
                @endphp
                <div class="mb-10 rounded-[2.5rem] border border-amber-500/30 bg-gradient-to-br from-amber-500/10 via-slate-900/90 to-slate-950 p-6 sm:p-8 shadow-2xl backdrop-blur-xl relative overflow-hidden" x-data="{ copied: false }">
                    <div class="absolute -right-10 -top-10 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-800/80">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-amber-500/20 border border-amber-500/30 rounded-2xl text-amber-400">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-amber-400">Kredensial Akun Pelanggan Baru</span>
                                <h3 class="text-2xl font-black text-white mt-0.5">{{ $cred['name'] }} <span class="text-slate-400 font-mono text-sm font-normal">({{ $cred['code'] }})</span></h3>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <button @click="navigator.clipboard.writeText('ID: {{ $cred['code'] }}\nEmail: {{ $cred['username'] }}\nPassword: {{ $cred['password'] }}\nPortal: {{ url('/login') }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold rounded-xl border border-slate-700 transition-all">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span x-text="copied ? '✓ Kredensial Disalin!' : 'Salin Kredensial'"></span>
                            </button>

                            @if(!empty($cleanPhone))
                                <a href="{{ $waUrl }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.3)] hover:shadow-[0_0_20px_rgba(16,185,129,0.5)] transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    Kirim via WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-slate-950/60 p-4 rounded-2xl border border-slate-800/80">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">ID Pelanggan</span>
                            <div class="text-base font-black text-amber-400 font-mono mt-1 select-all">{{ $cred['code'] }}</div>
                        </div>
                        <div class="bg-slate-950/60 p-4 rounded-2xl border border-slate-800/80">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Email Login</span>
                            <div class="text-base font-bold text-white mt-1 select-all">{{ $cred['username'] }}</div>
                        </div>
                        <div class="bg-slate-950/60 p-4 rounded-2xl border border-slate-800/80">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Password Default</span>
                            <div class="text-base font-black text-emerald-400 font-mono mt-1 select-all">{{ $cred['password'] }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="hidden md:block bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative mb-10">
                <div class="absolute -left-20 -top-20 w-72 h-72 bg-sky-500/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-800/60">
                            <tr>
                                <th class="px-8 py-5">Identitas Prospek</th>
                                <th class="px-6 py-5">Minat Layanan</th>
                                <th class="px-6 py-5">Lokasi Pemasangan</th>
                                <th class="px-6 py-5 text-center">Status Prospek</th>
                                <th class="px-8 py-5 text-center">Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @php /** @var \Illuminate\Support\Collection $leads */ @endphp
                            @forelse($leads as $lead)
                                <tr class="hover:bg-slate-800/40 transition-all duration-300 group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-sky-500/10 rounded-2xl flex items-center justify-center text-sky-400 border border-sky-500/20 group-hover:bg-sky-500 group-hover:text-white transition-colors duration-300 font-black text-lg uppercase shadow-sm">
                                                {{ substr($lead->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-white group-hover:text-sky-400 transition-colors text-base tracking-tight">{{ $lead->name }}</div>
                                                <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-0.5 flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                    {{ $lead->phone ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-xl">
                                            <span class="text-xs font-bold text-slate-300">{{ $lead->package->name ?? 'Belum Pilih Paket' }}</span>
                                        </div>
                                        <div class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Entry: {{ $lead->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="text-sm font-medium text-slate-300 leading-relaxed max-w-[250px] line-clamp-2" title="{{ $lead->address_installation }}">
                                            {{ $lead->address_installation }}
                                        </div>
                                        <div class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1.5 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                            {{ $lead->district ?? '-' }}, {{ $lead->city ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        @php
                                            $statusTheme = match ($lead->status) {
                                                'prospek' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-400', 'border' => 'border-blue-500/20', 'dot' => 'bg-blue-500'],
                                                'survey' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-400', 'border' => 'border-amber-500/20', 'dot' => 'bg-amber-500'],
                                                'instalasi' => ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-400', 'border' => 'border-indigo-500/20', 'dot' => 'bg-indigo-500'],
                                                'aktif', 'converted' => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/20', 'dot' => 'bg-emerald-500'],
                                                'batal' => ['bg' => 'bg-rose-500/10', 'text' => 'text-rose-400', 'border' => 'border-rose-500/20', 'dot' => 'bg-rose-500'],
                                                default => ['bg' => 'bg-slate-800', 'text' => 'text-slate-400', 'border' => 'border-slate-700', 'dot' => 'bg-slate-500'],
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $statusTheme['bg'] }} {{ $statusTheme['text'] }} {{ $statusTheme['border'] }} shadow-inner">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $statusTheme['dot'] }}"></span>
                                            {{ $lead->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('marketing.leads.show', $lead->id) }}" class="p-2.5 bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 rounded-xl transition-all border border-slate-700 shadow-sm" title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            @if ($lead->status !== 'converted' && $lead->status !== 'aktif')
                                                <a href="{{ route('marketing.leads.edit', $lead->id) }}" class="p-2.5 bg-slate-800 text-amber-500/80 hover:text-white hover:bg-amber-600 rounded-xl transition-all border border-slate-700 shadow-sm" title="Edit Data">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('marketing.leads.destroy', $lead->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDelete(this.closest('form'), '{{ addslashes($lead->name) }}')" class="p-2.5 bg-slate-800 text-rose-500/80 hover:text-white hover:bg-rose-600 rounded-xl transition-all border border-slate-700 shadow-sm" title="Hapus Data">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('marketing.leads.survey', $lead->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="button" onclick="confirmSurvey(this.closest('form'), '{{ addslashes($lead->name) }}')" class="p-2.5 bg-amber-500/10 text-amber-400 hover:text-white hover:bg-amber-500 rounded-xl transition-all border border-amber-500/30 shadow-sm" title="Kirim Permintaan Survey (Cek Kelayakan Jaringan)">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('marketing.leads.convert', $lead->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="button" onclick="confirmConvert(this.closest('form'), '{{ addslashes($lead->name) }}', '{{ $lead->odp_id }}', '{{ addslashes($lead->odp_port ?? '') }}')" class="p-2.5 bg-sky-500/10 text-sky-400 hover:text-white hover:bg-sky-500 rounded-xl transition-all border border-sky-500/30 shadow-sm" title="Konversi ke Pelanggan (Pilih ODP & Mulai Instalasi)">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <div class="px-3 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-[10px] text-emerald-400 font-black uppercase tracking-widest cursor-default" title="Sudah Dikonversi">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                    Selesai
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-4">
                                            <div class="w-24 h-24 bg-slate-800 rounded-3xl flex items-center justify-center text-slate-600 border border-slate-700 shadow-inner">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </div>
                                            <div class="max-w-md mx-auto mt-2">
                                                <h5 class="text-white font-bold text-lg tracking-tight">Belum Ada Prospek</h5>
                                                <p class="text-slate-500 text-sm mt-1 leading-relaxed">Sistem belum memiliki data prospek pelanggan. Silakan klik tombol "Tambah Prospek Baru" untuk mulai mendata calon pelanggan.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($leads->hasPages())
                    <div class="px-8 border-t border-slate-800/80 bg-slate-900/40">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>

            <div class="md:hidden space-y-4">
                @forelse($leads as $lead)
                    @php
                        $mobileStatusTheme = match ($lead->status) {
                            'prospek' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-400', 'border' => 'border-blue-500/20', 'dot' => 'bg-blue-500'],
                            'survey' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-400', 'border' => 'border-amber-500/20', 'dot' => 'bg-amber-500'],
                            'instalasi' => ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-400', 'border' => 'border-indigo-500/20', 'dot' => 'bg-indigo-500'],
                            'aktif', 'converted' => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/20', 'dot' => 'bg-emerald-500'],
                            'batal' => ['bg' => 'bg-rose-500/10', 'text' => 'text-rose-400', 'border' => 'border-rose-500/20', 'dot' => 'bg-rose-500'],
                            default => ['bg' => 'bg-slate-800', 'text' => 'text-slate-400', 'border' => 'border-slate-700', 'dot' => 'bg-slate-500'],
                        };
                    @endphp
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl sm:rounded-3xl border border-slate-800 overflow-hidden shadow-xl">
                        {{-- Header Card: Flex layout to prevent badge collision --}}
                        <div class="p-5 border-b border-slate-800/60 flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                <div class="w-12 h-12 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400 border border-sky-500/20 font-black text-xl uppercase shrink-0 shadow-sm">
                                    {{ substr($lead->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-base font-black text-white tracking-tight leading-snug break-words">{{ $lead->name }}</h4>
                                    <span class="text-xs text-slate-500 font-bold font-mono tracking-wider block mt-0.5">{{ $lead->phone ?? '-' }}</span>
                                </div>
                            </div>
                            <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider border {{ $mobileStatusTheme['bg'] }} {{ $mobileStatusTheme['text'] }} {{ $mobileStatusTheme['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $mobileStatusTheme['dot'] }}"></span>
                                {{ $lead->status }}
                            </span>
                        </div>

                        {{-- Details Section --}}
                        <div class="p-5 space-y-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest leading-none mb-1">Minat Layanan</p>
                                    <span class="text-white font-bold text-sm block truncate">{{ $lead->package->name ?? 'Belum Ditentukan' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest leading-none mb-1">Lokasi Pemasangan</p>
                                    <span class="text-slate-300 font-medium text-sm leading-snug line-clamp-2 break-words">{{ $lead->address_installation }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="p-4 bg-slate-800/30 border-t border-slate-800/60">
                            @if ($lead->status !== 'converted' && $lead->status !== 'aktif')
                                <div class="grid grid-cols-5 gap-2">
                                    <a href="{{ route('marketing.leads.show', $lead->id) }}" class="flex items-center justify-center py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-slate-400 hover:text-white transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('marketing.leads.edit', $lead->id) }}" class="flex items-center justify-center py-2.5 bg-slate-800 hover:bg-amber-600/20 border border-slate-700 hover:border-amber-500/50 rounded-xl text-amber-500/80 hover:text-amber-400 transition-colors" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('marketing.leads.destroy', $lead->id) }}" method="POST" class="flex">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this.closest('form'), '{{ addslashes($lead->name) }}')" class="w-full flex items-center justify-center py-2.5 bg-slate-800 hover:bg-rose-600/20 border border-slate-700 hover:border-rose-500/50 rounded-xl text-rose-500/80 hover:text-rose-400 transition-colors" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('marketing.leads.survey', $lead->id) }}" method="POST" class="flex">
                                        @csrf
                                        <button type="button" onclick="confirmSurvey(this.closest('form'), '{{ addslashes($lead->name) }}')" class="w-full flex items-center justify-center py-2.5 bg-amber-500/10 hover:bg-amber-500 border border-amber-500/30 rounded-xl text-amber-400 hover:text-white transition-all shadow-sm" title="Kirim Permintaan Survey">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('marketing.leads.convert', $lead->id) }}" method="POST" class="flex">
                                        @csrf
                                        <button type="button" onclick="confirmConvert(this.closest('form'), '{{ addslashes($lead->name) }}', '{{ $lead->odp_id }}', '{{ addslashes($lead->odp_port ?? '') }}')" class="w-full flex items-center justify-center py-2.5 bg-sky-500/10 hover:bg-sky-500 border border-sky-500/30 rounded-xl text-sky-400 hover:text-white transition-all shadow-sm" title="Konversi ke Pelanggan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="flex items-center gap-2.5">
                                    <a href="{{ route('marketing.leads.show', $lead->id) }}" class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-slate-300 hover:text-white font-bold text-xs uppercase tracking-wider transition-colors">
                                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat Detail
                                    </a>
                                    <div class="inline-flex items-center gap-1.5 px-3 py-2.5 text-[11px] text-emerald-400 font-black tracking-wider uppercase bg-emerald-500/10 rounded-xl border border-emerald-500/20 shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        Telah Dikonversi
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 shadow-xl">
                        <p class="text-slate-500 font-medium text-sm">Belum ada data prospek.</p>
                    </div>
                @endforelse
            </div>

            @if($leads->hasPages())
                <div class="md:hidden mt-8 bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 px-6">
                    {{ $leads->links() }}
                </div>
            @endif

        </div>
    </div>
    <script>
        window.netManagerOdps = @json($odps ?? []);
    </script>
</x-app-layout>