<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white mb-8">Pemeliharaan Sistem</h2>

            @if (session('success'))
                <div class="mb-4 rounded-xl bg-green-50 p-4 border border-green-200">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Maintenance Mode -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-slate-900 rounded-xl shadow-sm border border-slate-800 p-6">
                    <h3 class="font-bold text-lg text-white mb-4">Mode Pemeliharaan</h3>
                    <p class="text-slate-300 mb-4">Tutup sistem untuk maintenance</p>

                    <form action="{{ route('superadmin.maintenance.maintenanceMode') }}" method="POST">
                        @csrf
                        <label class="flex items-center mb-4">
                            <input type="checkbox" name="maintenance_mode" value="1" @checked($maintenanceMode)
                                class="rounded">
                            <span class="ml-2 text-sm font-medium text-slate-300">Aktifkan Mode Pemeliharaan</span>
                        </label>
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Update
                        </button>
                    </form>
                </div>

                <!-- Database Info -->
                <div class="bg-slate-900 rounded-xl shadow-sm border border-slate-800 p-6">
                    <h3 class="font-bold text-lg text-white mb-4">Informasi Database</h3>
                    <div class="space-y-2 text-sm">
                        <p><strong>Ukuran:</strong> {{ $cacheSize }}</p>
                        <p><strong>Backup Terakhir:</strong> {{ $lastBackup }}</p>
                    </div>

                    <form action="{{ route('superadmin.maintenance.backupDatabase') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 text-sm">
                            Backup Sekarang
                        </button>
                    </form>
                </div>

                <!-- Cache Management -->
                <div class="bg-slate-900 rounded-xl shadow-sm border border-slate-800 p-6">
                    <h3 class="font-bold text-lg text-white mb-4">Manajemen Cache</h3>

                    <form action="{{ route('superadmin.maintenance.clearCache') }}" method="POST">
                        @csrf
                        <button type="button" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm mb-2"
                            onclick="confirmDelete(this.closest('form'), 'seluruh cache sistem')">
                            Hapus Cache
                        </button>
                    </form>

                    <form action="{{ route('superadmin.maintenance.optimizeDatabase') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
                            Optimasi Database
                        </button>
                    </form>
                </div>
            </div>

            <!-- Maintenance Actions -->
            <div class="bg-slate-900 rounded-2xl shadow-xl border border-slate-800 p-6">
                <h3 class="font-bold text-xl text-white mb-6">Aksi Pemeliharaan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-slate-800 rounded-lg p-4">
                        <h4 class="font-semibold text-white mb-2">Backup Database</h4>
                        <p class="text-sm text-slate-300 mb-4">Buat backup lengkap dari database sistem</p>
                        <form action="{{ route('superadmin.maintenance.backupDatabase') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 text-sm">
                                Jalankan Backup
                            </button>
                        </form>
                    </div>

                    <div class="border border-slate-800 rounded-lg p-4">
                        <h4 class="font-semibold text-white mb-2">Bersihkan Log</h4>
                        <p class="text-sm text-slate-300 mb-4">Hapus file log yang sudah lama</p>
                        <form action="{{ route('superadmin.maintenance.clearLogs') }}" method="POST">
                            @csrf
                            <button type="button" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm"
                                onclick="confirmDelete(this.closest('form'), 'berkas log sistem lama')">
                                Bersihkan Log
                            </button>
                        </form>
                    </div>

                    <div class="border border-slate-800 rounded-lg p-4">
                        <h4 class="font-semibold text-white mb-2">Optimize Database</h4>
                        <p class="text-sm text-slate-300 mb-4">Optimalkan performa database</p>
                        <form action="{{ route('superadmin.maintenance.optimizeDatabase') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
                                Jalankan Optimasi
                            </button>
                        </form>
                    </div>

                    <div class="border border-slate-800 rounded-lg p-4">
                        <h4 class="font-semibold text-white mb-2">Clear Cache</h4>
                        <p class="text-sm text-slate-300 mb-4">Hapus semua cache aplikasi</p>
                        <form action="{{ route('superadmin.maintenance.clearCache') }}" method="POST">
                            @csrf
                            <button type="button" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm"
                                onclick="confirmDelete(this.closest('form'), 'seluruh cache aplikasi')">
                                Clear Cache
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
