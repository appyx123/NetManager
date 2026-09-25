<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NetworkAsset;
use Illuminate\Http\Request;

class RouterController extends Controller
{
    public function index()
    {
        $routers = NetworkAsset::where('type', 'Router')->orWhere('type', 'OLT')->orWhere('type', 'AP')->orWhere('type', 'ODP')->paginate(15);

        return view('admin.routers.index', compact('routers'));
    }

    public function create()
    {
        $types = ['OLT', 'Router', 'AP', 'ODP'];
        return view('admin.routers.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'ip_address' => 'required|ip',
            'brand' => 'required|string|max:255',
            'type' => 'required|in:OLT,Router,AP,ODP',
            'is_active' => 'required|boolean',
        ]);

        NetworkAsset::create($validated);

        return redirect()->route('admin.routers.index')->with('success', 'Perangkat jaringan berhasil ditambahkan');
    }

    public function edit(NetworkAsset $router)
    {
        $types = ['OLT', 'Router', 'AP', 'ODP'];
        return view('admin.routers.edit', compact('router', 'types'));
    }

    public function update(Request $request, NetworkAsset $router)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'ip_address' => 'required|ip',
            'brand' => 'required|string|max:255',
            'type' => 'required|in:OLT,Router,AP,ODP',
            'is_active' => 'required|boolean',
        ]);

        $router->update($validated);

        return redirect()->route('admin.routers.index')->with('success', 'Perangkat jaringan berhasil diperbarui');
    }

    public function testConnection(NetworkAsset $router)
    {
        // Tes koneksi non-blocking ke port API MikroTik (default 8728)
        $port = (int) config('services.mikrotik.port', 8728);
        $isOnline = $this->checkSocket($router->ip_address, $port, 2);

        return response()->json([
            'status' => $isOnline ? 'online' : 'offline',
            'message' => $isOnline ? 'Koneksi ke perangkat berhasil (Online)' : 'Perangkat tidak merespons (Offline / Timeout)',
        ]);
    }

    public function destroy(NetworkAsset $router)
    {
        $router->delete();
        return redirect()->route('admin.routers.index')->with('success', 'Perangkat jaringan berhasil dihapus');
    }

    /**
     * Pengecekan socket non-blocking dengan timeout pendek untuk menghindari thread hanging
     */
    private function checkSocket(string $host, int $port = 8728, int $timeout = 2): bool
    {
        $errno = 0;
        $errstr = '';

        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);

        if (is_resource($connection)) {
            fclose($connection);
            return true;
        }

        return false;
    }
}
