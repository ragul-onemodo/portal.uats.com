<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApplicationController extends Controller
{
    protected string $module = 'application';

    public function index()
    {
        return $this->view('index');
    }

    public function datatable(Request $request)
    {
        $query = Application::query();

        return DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('is_active', function ($row) {
                return $row->is_active ? 'Active' : 'Inactive';
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

            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return $this->view('create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:100|unique:applications,code',
            'description' => 'nullable|string',
            'webhook_url' => 'nullable|url|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        Application::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
            'webhook_url' => $validated['webhook_url'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Application created successfully',
        ]);
    }

    public function edit(string $id)
    {
        return $this->view('edit', [
            'application' => Application::findOrFail($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $application = Application::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:100|unique:applications,code,' . $id,
            'description' => 'nullable|string',
            'webhook_url' => 'nullable|url|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $application->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
            'webhook_url' => $validated['webhook_url'] ?? null,
            'is_active' => $validated['is_active'] ?? $application->is_active,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Application updated successfully',
        ]);
    }

    public function destroy(string $id)
    {
        Application::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Application deleted successfully',
        ]);
    }
}
