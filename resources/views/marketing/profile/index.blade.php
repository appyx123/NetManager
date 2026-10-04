<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-blue-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Profile Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-24"></div>
                        <div class="px-6 pb-6 -mt-16 relative z-10">
                            <div class="flex justify-center mb-4">
                                <div class="h-32 w-32 rounded-full bg-blue-100 border-4 border-white shadow-lg flex items-center justify-center text-blue-700 font-bold text-4xl">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-blue-900 text-center">{{ auth()->user()->name }}</h3>
                            <p class="text-blue-600 text-center text-sm mt-1">Tim Marketing</p>
                            <div class="mt-6 space-y-3">
                                <div class="flex items-center text-blue-700">
                                    <svg class="w-4 h-4 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs">{{ auth()->user()->email }}</span>
                                </div>
                                <div class="flex items-center text-blue-700">
                                    <svg class="w-4 h-4 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs">Bergabung: {{ auth()->user()->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 mt-6">
                        <h4 class="text-lg font-bold text-blue-900 mb-4">📊 Target Bulanan</h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-blue-700">Lead Baru</span>
                                    <span class="text-sm font-bold text-yellow-700">45/50</span>
                                </div>
                                <div class="w-full bg-blue-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-2 rounded-full" style="width: 90%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-blue-700">Konversi</span>
                                    <span class="text-sm font-bold text-green-700">12/15</span>
                                </div>
                                <div class="w-full bg-blue-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-400 to-green-500 h-2 rounded-full" style="width: 80%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-blue-700">Follow-up</span>
                                    <span class="text-sm font-bold text-blue-700">28/30</span>
                                </div>
                                <div class="w-full bg-blue-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" style="width: 93%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profile Form --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Personal Information --}}
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6">
                        <h4 class="text-lg font-bold text-blue-900 mb-6">Informasi Personal</h4>
                        <form class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-blue-900 mb-2">Nama Lengkap</label>
                                    <input type="text" value="{{ auth()->user()->name }}" 
                                        class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-blue-900 mb-2">Email</label>
                                    <input type="email" value="{{ auth()->user()->email }}"
                                        class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-blue-900 mb-2">Telepon</label>
                                    <input type="tel" placeholder="+62 812 3456 7890"
                                        class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-blue-900 mb-2">Wilayah Kerja</label>
                                    <select class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option>Kota Jakarta</option>
                                        <option>Kota Bandung</option>
                                        <option>Kota Surabaya</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-blue-900 mb-2">Alamat</label>
                                <textarea rows="3" placeholder="Masukkan alamat lengkap..."
                                    class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit" class="px-6 py-2 bg-yellow-400 text-blue-900 rounded-lg font-bold hover:bg-yellow-500 transition">
                                    💾 Simpan Perubahan
                                </button>
                                <button type="button" class="px-6 py-2 bg-blue-100 text-blue-700 rounded-lg font-bold hover:bg-blue-200 transition">
                                    ↺ Batalkan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Security Settings --}}
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6">
                        <h4 class="text-lg font-bold text-blue-900 mb-6">Keamanan</h4>
                        <div class="space-y-4">
                            <button class="w-full flex items-center justify-between p-4 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                <div class="text-left">
                                    <p class="font-semibold text-blue-900">Edit Password</p>
                                    <p class="text-xs text-blue-600 mt-1">Terakhir diubah: 60 hari lalu</p>
                                </div>
                                <span class="text-blue-600">→</span>
                            </button>
                            <button class="w-full flex items-center justify-between p-4 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                <div class="text-left">
                                    <p class="font-semibold text-blue-900">Autentikasi Dua Faktor</p>
                                    <p class="text-xs text-green-600 mt-1">✓ Diaktifkan</p>
                                </div>
                                <span class="text-blue-600">→</span>
                            </button>
                            <button class="w-full flex items-center justify-between p-4 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                <div class="text-left">
                                    <p class="font-semibold text-blue-900">Sesi Aktif</p>
                                    <p class="text-xs text-blue-600 mt-1">1 sesi aktif saat ini</p>
                                </div>
                                <span class="text-blue-600">→</span>
                            </button>
                        </div>
                    </div>

                    {{-- Logout --}}
                    <div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full px-6 py-3 bg-red-100 text-red-700 rounded-lg font-bold hover:bg-red-200 transition">
                                🚪 Logout
                            </button>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
