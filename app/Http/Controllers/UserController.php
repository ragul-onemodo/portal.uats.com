<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected string $module = 'user';

    public function __construct()
    {
        //

        $this->pageData['entities'] = DB::table('entities')
            ->select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $this->pageData['pageTitle'] = 'Manage Users';
        $this->pageData['roles'] = DB::table('roles')
            ->select('id', 'name')
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->pageData['pageTitle'] = 'Manage Users';
        return $this->view('index', $this->pageData);
    }

    /**
     * Datatable endpoint
     */
    public function datatable(Request $request)
    {
        $query = User::query()
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.status',
                'entity_users.entity_id'
            )
            ->leftJoin('entity_users', 'entity_users.user_id', '=', 'users.id');

        return DataTables::of($query)
            ->addIndexColumn() // DT_RowIndex

            ->editColumn('status', function ($row) {
                return $row->status ? 'Active' : 'Inactive';
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->view('create', $this->pageData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8',
            'status' => 'sometimes|boolean',
            'entity_id' => 'required|exists:entities,id',

            'role_id' => 'required|exists:roles,id',
        ]);

        DB::transaction(function () use ($validated) {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'] ?? true,
            ]);

            DB::table('entity_users')->insert([
                'entity_id' => $validated['entity_id'],
                'user_id' => $user->id,
            ]);

            $user->assignRole(Role::find($validated['role_id']));
        });

        return response()->json([
            'status' => true,
            'message' => 'User created successfully'
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
        $user = User::query()
            ->select('users.*', 'entity_users.entity_id')
            ->leftJoin('entity_users', 'entity_users.user_id', '=', 'users.id')
            ->where('users.id', $id)
            ->firstOrFail();

        $this->pageData['user'] = $user;


        return $this->view('edit', $this->pageData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'status' => 'sometimes|boolean',
            'entity_id' => 'required|exists:entities,id',

            'role_id' => 'nullable|exists:roles,id',

        ]);

        DB::transaction(function () use ($validated, $user) {

            if (isset($validated['role_id'])) {
                $user->roles()->detach();
            }

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'status' => $validated['status'] ?? $user->status,
                'password' => isset($validated['password'])
                    ? Hash::make($validated['password'])
                    : $user->password,
            ]);

            DB::table('entity_users')
                ->where('user_id', $user->id)
                ->update([
                    'entity_id' => $validated['entity_id'],
                ]);

            $user->assignRole(Role::find($validated['role_id']));


        });

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        DB::transaction(function () use ($user) {

            DB::table('entity_users')
                ->where('user_id', $user->id)
                ->delete();

            $user->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
