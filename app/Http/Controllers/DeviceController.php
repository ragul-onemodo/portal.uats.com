<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DeviceController extends Controller
{
    protected string $module = 'device';

    public function __construct()
    {
        $this->pageData['entities'] = DB::table('entities')
            ->select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->pageData['pageTitle'] = 'Manage Devices';
        return $this->view('index', $this->pageData);
    }

    /**
     * Datatable endpoint
     */
    public function datatable(Request $request)
    {
        $query = Device::query()
            ->select(
                'devices.id',
                'devices.device_name',
                'devices.device_type',
                'devices.api_key',
                'devices.is_active',
                'devices.last_heartbeat_at',
                'devices.device_uid',
                'entities.name as entity_name'
            )
            ->leftJoin('entities', 'entities.id', '=', 'devices.entity_id');

        if ($request->filled('entity_id')) {
            $query->where('devices.entity_id', $request->entity_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('status_badge', function ($row) {
                if (!$row->is_active) {
                    return '<span class="badge bg-secondary">Disabled</span>';
                }

                if (!$row->last_heartbeat_at) {
                    return '<span class="badge bg-dark">Never Seen</span>';
                }

                $seconds = Carbon::parse($row->last_heartbeat_at)->diffInSeconds(now());

                if ($seconds <= 90) {
                    return '<span class="badge bg-success">Online</span>';
                }

                if ($seconds <= 300) {
                    return '<span class="badge bg-warning text-dark">Degraded</span>';
                }

                return '<span class="badge bg-danger">Offline</span>';
            })

            ->addColumn('api_key_masked', function ($row) {
                return '
                <div class="d-flex align-items-center gap-2 api-key-wrapper">
                    <span class="api-key masked">••••••••••••••••</span>
                    <button type="button"
                            class="btn btn-sm btn-light toggle-api-key"
                            data-value="' . e($row->api_key) . '">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>
            ';
            })

            ->editColumn('last_heartbeat_at', function ($row) {
                return $row->last_heartbeat_at
                    ? Carbon::parse($row->last_heartbeat_at)->diffForHumans()
                    : '-';
            })

            ->addColumn('action', function ($row) {
                return '
                <a href="' . route('device.stat', $row->id) . '" class="btn btn-sm btn-warning rounded-pill" data-id="' . $row->id . '">

                    <i class="fa fa-tachometer"></i>

                </a>
                <button class="btn btn-sm btn-primary btn-edit rounded-pill" data-id="' . $row->id . '">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-delete rounded-pill" data-id="' . $row->id . '">
                    <i class="fas fa-trash"></i>
                </button>';
            })

            ->rawColumns(['status_badge', 'api_key_masked', 'action'])
            ->make(true);
    }


    public function apiList(Request $request)
    {
        $query = Device::query()
            ->select(
                'devices.id',
                'devices.device_name',
                'devices.device_type',
                'devices.api_key',
                'devices.is_active',
                'devices.last_heartbeat_at',
                'entities.name as entity_name'
            )
            ->leftJoin('entities', 'entities.id', '=', 'devices.entity_id');

        if ($request->filled('entity_id')) {
            $query->where('devices.entity_id', $request->entity_id);
        }

        $devices = $query->get();

        return response()->json([
            'success' => true,
            'data' => $devices,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return $this->view('create', $this->pageData);
    }

    /**
     * Store device
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_name' => 'required|string|max:100',
            'device_type' => 'required|string|max:50',
            'entity_id' => 'required|exists:entities,id',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($validated) {

            Device::create([
                'device_name' => $validated['device_name'],
                'device_type' => $validated['device_type'],
                'entity_id' => $validated['entity_id'],
                'is_active' => $validated['is_active'] ?? true,
                'api_key' => $this->generateApiKey(),
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => 'Device created successfully',
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(string $id)
    {
        $this->pageData['device'] = Device::findOrFail($id);
        return $this->view('edit', $this->pageData);
    }

    /**
     * Update device
     */
    public function update(Request $request, string $id)
    {
        $device = Device::findOrFail($id);

        $validated = $request->validate([
            'device_name' => 'required|string|max:100',
            'device_type' => 'required|string|max:50',
            'entity_id' => 'required|exists:entities,id',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($validated, $device) {

            $device->update([
                'device_name' => $validated['device_name'],
                'device_type' => $validated['device_type'],
                'entity_id' => $validated['entity_id'],
                'is_active' => $validated['is_active'] ?? $device->is_active,
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => 'Device updated successfully',
        ]);
    }

    /**
     * Delete device
     */
    public function destroy(string $id)
    {
        $device = Device::findOrFail($id);

        DB::transaction(function () use ($device) {
            $device->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Device deleted successfully',
        ]);
    }

    public function stat(string $id)
    {
        $this->pageData['device'] = Device::findOrFail($id);
        $this->pageData['pageTitle'] = 'Device System Stats';
        $this->pageData['systemStat'] = $this->pageData['device']->systemStats;
        return $this->view('stat', $this->pageData);
    }

    /**
     * Generate 18-character salted API key
     */
    private function generateApiKey(): string
    {
        do {
            $raw = hash_hmac(
                'sha256',
                Str::random(40),
                config('app.key')
            );

            // Base62-ish, uppercase, fixed length
            $key = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', base64_encode(hex2bin($raw))), 0, 18));
        } while (
            DB::table('devices')->where('api_key', $key)->exists()
        );

        return $key;
    }
}
