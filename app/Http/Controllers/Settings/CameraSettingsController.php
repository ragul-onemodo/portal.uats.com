<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\EntityCamera;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CameraSettingsController extends Controller
{
    protected string $module = 'settings.camera';

    public function __construct()
    {
        $this->pageData['entities'] = DB::table('entities')
            ->select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Display camera listing
     */
    public function index()
    {
        $this->pageData['pageTitle'] = 'Manage Cameras';
        return $this->view('index', $this->pageData);
    }

    /**
     * Datatable endpoint
     */
    public function datatable(Request $request)
    {
        $query = EntityCamera::query()
            ->select(
                'entity_cameras.id',
                'entity_cameras.name',
                'entity_cameras.ip_address',
                'entity_cameras.snapshot_url',
                'entity_cameras.is_primary',
                'entity_cameras.is_secondary',
                'entity_cameras.is_active',
                'entities.name as entity_name'
            )
            ->leftJoin('entities', 'entities.id', '=', 'entity_cameras.entity_id');

        if ($request->filled('entity_id')) {
            $query->where('entity_cameras.entity_id', $request->entity_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('camera_role', function ($row) {
                if ($row->is_primary) {
                    return '<span class="badge bg-primary">Primary</span>';
                }

                if ($row->is_secondary) {
                    return '<span class="badge bg-info">Secondary</span>';
                }

                return '<span class="badge bg-secondary">Other</span>';
            })

            ->addColumn('status_badge', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Disabled</span>';
            })

            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-sm btn-primary btn-edit rounded-pill" data-id="' . $row->id . '">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-delete rounded-pill" data-id="' . $row->id . '">
                    <i class="fas fa-trash"></i>
                </button>';
            })

            ->rawColumns(['camera_role', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return $this->view('create', $this->pageData);
    }

    /**
     * Store camera
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'entity_id' => 'required|exists:entities,id',
            'name' => 'required|string|max:100',
            'ip_address' => 'required|ip',
            'username' => 'nullable|string|max:100',
            'password' => 'nullable|string|max:255',
            'snapshot_slug' => 'required|string|max:255',
            'is_primary' => 'sometimes|boolean',
            'is_secondary' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($validated) {

            if (!empty($validated['is_primary'])) {
                EntityCamera::where('entity_id', $validated['entity_id'])
                    ->update(['is_primary' => false]);
            }

            if (!empty($validated['is_secondary'])) {
                EntityCamera::where('entity_id', $validated['entity_id'])
                    ->update(['is_secondary' => false]);
            }

            $snapshot_url = $validated['username'] && $validated['password']
                ? 'http://' . urlencode($validated['username']) . ':' . urlencode($validated['password']) . '@' . $validated['ip_address'] . '/' . ltrim($validated['snapshot_slug'], '/')
                : 'http://' . $validated['ip_address'] . '/' . ltrim($validated['snapshot_slug'], '/');

            $camera = EntityCamera::create([
                'entity_id' => $validated['entity_id'],
                'name' => $validated['name'],
                'ip_address' => $validated['ip_address'],
                'username' => $validated['username'] ?? null,
                'password' => $validated['password'] ?? null,
                'snapshot_url' => $snapshot_url,
                'is_primary' => $validated['is_primary'] ?? false,
                'is_secondary' => $validated['is_secondary'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            Cache::forget("entity:{$camera->entity_id}:camera_urls");
        });



        return response()->json([
            'status' => true,
            'message' => 'Camera created successfully',
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(string $id)
    {
        $this->pageData['camera'] = EntityCamera::findOrFail($id);
        return $this->view('edit', $this->pageData);
    }

    /**
     * Update camera
     */
    public function update(Request $request, string $id)
    {
        $camera = EntityCamera::findOrFail($id);

        $validated = $request->validate([
            'entity_id' => 'required|exists:entities,id',
            'name' => 'required|string|max:100',
            'ip_address' => 'required|ip',
            'username' => 'nullable|string|max:100',
            'password' => 'nullable|string|max:255',
            'snapshot_slug' => 'required|string|max:255',
            'is_primary' => 'sometimes|boolean',
            'is_secondary' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($validated, $camera) {

            // Enforce single primary
            if (!empty($validated['is_primary'])) {
                EntityCamera::where('entity_id', $validated['entity_id'])
                    ->where('id', '!=', $camera->id)
                    ->update(['is_primary' => false]);
            }

            // Enforce single secondary
            if (!empty($validated['is_secondary'])) {
                EntityCamera::where('entity_id', $validated['entity_id'])
                    ->where('id', '!=', $camera->id)
                    ->update(['is_secondary' => false]);
            }

            // Preserve existing credentials if not provided
            $username = $validated['username'] ?? $camera->username;
            $password = !empty($validated['password'])
                ? $validated['password']
                : $camera->password;

            // Rebuild snapshot URL (same logic as store)
            if ($username || $password) {

                if ($username === null) {
                    $username = $camera->username;
                }

                if ($password === null) {
                    $password = $camera->password;
                }

                $snapshot_url =
                    'http://' .
                    urlencode($username) . ':' .
                    urlencode($password) . '@' .
                    $validated['ip_address'] . '/' .
                    ltrim($validated['snapshot_slug'], '/');
            } else {
                $snapshot_url =
                    'http://' .
                    $validated['ip_address'] . '/' .
                    ltrim($validated['snapshot_slug'], '/');
            }

            $camera->update([
                'entity_id' => $validated['entity_id'],
                'name' => $validated['name'],
                'ip_address' => $validated['ip_address'],
                'username' => $username,
                'password' => $password,
                'snapshot_url' => $snapshot_url,
                'is_primary' => $validated['is_primary'] ?? $camera->is_primary,
                'is_secondary' => $validated['is_secondary'] ?? $camera->is_secondary,
                'is_active' => $validated['is_active'] ?? $camera->is_active,
            ]);
        });

        Cache::forget("entity:{$camera->entity_id}:camera_urls");

        return response()->json([
            'status' => true,
            'message' => 'Camera updated successfully',
        ]);
    }


    /**
     * Delete camera
     */
    public function destroy(string $id)
    {
        $camera = EntityCamera::findOrFail($id);

        DB::transaction(function () use ($camera) {
            $camera->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Camera deleted successfully',
        ]);
    }
}
