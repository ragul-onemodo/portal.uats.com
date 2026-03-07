<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Application;
use App\Models\EntityApplication;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EntityApplicationController extends Controller
{
    protected string $module = 'entity-application';

    public function __construct()
    {
        $this->pageData['entities'] = Entity::orderBy('name')->get();
    }

    public function index()
    {
        $this->pageData['pageTitle'] = 'Manage Entity Applications';
        return $this->view('index', $this->pageData);
    }

    public function datatable(Request $request)
    {
        $query = EntityApplication::query()
            ->with(['entity:id,name', 'application:id,name']);


        if ($request->filled('entity_id')) {
            $query->where('entity_id', $request->entity_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('entity', fn($row) => $row->entity->name ?? '-')
            ->addColumn('application', fn($row) => $row->application->name ?? '-')

            ->editColumn(
                'is_active',
                fn($row) =>
                $row->is_active ? 'Active' : 'Inactive'
            )

            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-sm btn-primary btn-edit rounded-pill" data-id="' . $row->id . '">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-delete rounded-pill" data-id="' . $row->id . '">
                    <i class="fas fa-trash"></i>
                </button>';
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return $this->view('create', [
            'entities' => Entity::orderBy('name')->get(),
            'applications' => Application::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entity_id' => 'required|exists:entities,id',
            'application_id' => 'required|exists:applications,id',
            'company_reference' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        EntityApplication::create([
            'entity_id' => $validated['entity_id'],
            'application_id' => $validated['application_id'],
            'company_reference' => $validated['company_reference'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Application mapped to entity successfully',
        ]);
    }

    public function edit(string $id)
    {
        return $this->view('edit', [
            'entityApplication' => EntityApplication::findOrFail($id),
            'entities' => Entity::orderBy('name')->get(),
            'applications' => Application::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $entityApplication = EntityApplication::findOrFail($id);

        $validated = $request->validate([
            'entity_id' => 'required|exists:entities,id',
            'application_id' => 'required|exists:applications,id',
            'company_reference' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $entityApplication->update([
            'entity_id' => $validated['entity_id'],
            'application_id' => $validated['application_id'],
            'company_reference' => $validated['company_reference'],
            'is_active' => $validated['is_active'] ?? $entityApplication->is_active,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Entity application updated successfully',
        ]);
    }

    public function destroy(string $id)
    {
        EntityApplication::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Entity application removed successfully',
        ]);
    }
}
