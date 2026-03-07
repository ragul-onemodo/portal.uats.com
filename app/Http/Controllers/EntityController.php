<?php

namespace App\Http\Controllers;

use App\DataTables\EntityDataTable;
use App\Models\Entity;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EntityController extends Controller
{
    protected string $module = 'entity';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $this->pageData['pageTitle'] = 'Manage Entities';
        return $this->view('index', $this->pageData);
    }


    public function datatable(Request $request)
    {

        $query = Entity::query();

        return DataTables::of($query)
            ->addIndexColumn() // DT_RowIndex

            ->editColumn('is_active', function ($row) {
                return $row->is_active ? 'Active' : 'Inactive';
            })

            ->editColumn('integration_enabled', function ($row) {
                return $row->integration_enabled ? 'Yes' : 'No';
            })

            ->addColumn('action', function ($row) {
                return '
              <button class="btn btn-sm btn-primary btn-edit rounded-pill" data-id="' . $row->id . '">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-delete btn-danger rounded-pill" data-id="' . $row->id . '">
                <i class="fas fa-trash"></i>
              </button>';
            })

            ->rawColumns(['action'])
            ->make(true);

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return $this->view('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'nullable|string|max:150',
            'is_active' => 'sometimes|boolean',
            'integration_enabled' => 'sometimes|boolean',
        ]);

        $entity = Entity::create([
            'name' => $validated['name'],
            'location' => $validated['location'] ?? null,

            'directory_path' => $this->buildDirectoryPath($validated['name']),

            // Respect defaults if not sent
            'is_active' => $validated['is_active'] ?? true,
            'integration_enabled' => $validated['integration_enabled'] ?? false,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Entity Created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //

        return $this->view('edit', [
            'entity' => Entity::findOrFail($id),
        ]);

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        // 1. Find entity
        $entity = Entity::findOrFail($id);

        // 2. Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'nullable|string|max:150',
            'is_active' => 'sometimes|boolean',
            'integration_enabled' => 'sometimes|boolean',
        ]);

        // 3. Update entity
        $entity->update([
            'name' => $validated['name'],
            'location' => $validated['location'] ?? null,
            'is_active' => $validated['is_active'] ?? $entity->is_active,
            'integration_enabled' => $validated['integration_enabled'] ?? $entity->integration_enabled,
        ]);

        // 4. Return JSON response

        return response()->json([
            'status' => true,
            'message' => 'Entity updated successfully'
        ]);


    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        // 1. Find entity or fail
        $entity = Entity::findOrFail($id);

        // 2. Delete entity
        $entity->delete();

        // 3. Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Entity deleted successfully'
        ]);
    }


    private function buildDirectoryPath(string $name): string
    {
        // Sanitize name to create a directory-friendly path
        $sanitized = strtolower(trim(preg_replace('/[^A-Za-z0-9\-]+/', '_', $name)));
        $uuid = uniqid('entity_', true);

        return $uuid . "_" . $sanitized;
    }
}

