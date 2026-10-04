<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RoleAccessController extends Controller
{
    public function index()
    {
        $roles = ['super_admin', 'admin', 'marketing', 'technician', 'customer'];
        $permissions = $this->getAvailablePermissions();
        $rolePermissions = RolePermission::all()->groupBy('role');

        return view('superadmin.roles.index', compact('roles', 'permissions', 'rolePermissions'));
    }

    public function updatePermissions(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|string',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        // Delete existing permissions for this role
        RolePermission::where('role', $validated['role'])->delete();

        // Create new permissions
        foreach ($validated['permissions'] ?? [] as $permission) {
            RolePermission::create([
                'role' => $validated['role'],
                'permission' => $permission,
            ]);
        }

        return redirect()->back()->with('success', 'Hak akses berhasil diperbarui.');
    }

    private function getAvailablePermissions()
    {
        return [
            'admin' => [
                'dashboard.view' => 'Lihat Dashboard',
                'customers.view' => 'Lihat Pelanggan',
                'customers.edit' => 'Edit Pelanggan',
                'customers.isolate' => 'Isolir Pelanggan',
                'packages.manage' => 'Kelola Paket',
                'billing.view' => 'Lihat Billing',
                'billing.create' => 'Buat Invoice',
                'reports.view' => 'Lihat Laporan',
            ],
            'marketing' => [
                'leads.view' => 'Lihat Lead',
                'leads.manage' => 'Kelola Lead',
            ],
            'technician' => [
                'tickets.view' => 'Lihat Ticket',
                'tickets.manage' => 'Kelola Ticket',
            ],
        ];
    }
}
