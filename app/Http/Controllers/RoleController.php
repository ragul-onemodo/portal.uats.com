<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

use App\Permissions\PermissionManifest;

class RoleController extends Controller
{
    protected string $module = 'role';

    public function __construct()
    {
        //

        $this->pageData['pageTitle'] = 'Manage Roles';
    }

    public function index()
    {
        return $this->view('index', $this->pageData);
    }

    public function datatable(Request $request)
    {
        $query = Role::query()->where('guard_name', 'web');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('permissions', function ($role) {
                return $role->permissions->count();
            })
            ->addColumn('action', function ($role) {
                return '
                <button class="btn btn-sm btn-primary btn-edit rounded-pill" data-id="' . $role->id . '">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-delete rounded-pill" data-id="' . $role->id . '">
                    <i class="fas fa-trash"></i>
                </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return $this->view('create', [
            'permissions' => PermissionManifest::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return response()->json([
            'status' => true,
            'message' => 'Role created successfully'
        ]);
    }

    public function edit(string $id)
    {
        return $this->view('edit', [
            'role' => Role::findOrFail($id),
            'permissions' => PermissionManifest::all()
        ]);
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return response()->json([
            'status' => true,
            'message' => 'Role updated successfully'
        ]);
    }

    public function destroy(string $id)
    {
        Role::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }
}
