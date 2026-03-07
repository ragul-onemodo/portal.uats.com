<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Entity;
use App\Models\Trip;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TripController extends Controller
{
    /* ================= INDEX ================= */
    public function index()
    {
        return view('pages.trips.index');
    }

    /* ================= DATATABLE ================= */
    public function datatable()
    {
        $query = Trip::with(['device', 'entity']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('device_name', fn ($row) => optional($row->device)->device_name ?? '-')
            ->addColumn('entity_name', fn ($row) => optional($row->entity)->name ?? '-')
            ->addColumn('direction', function ($row) {
                return $row->direction == 'IN'
                    ? '<span class="badge bg-success">IN</span>'
                    : '<span class="badge bg-danger">OUT</span>';
            })
            // ->addColumn('action', function ($row) {
            //     return '
            //         <button class="btn btn-sm btn-primary edit" data-id="'.$row->id.'">Edit</button>
            //         <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">Delete</button>
            //     ';
            // })
            ->rawColumns(['direction', 'action'])
            ->make(true);
    }

    /* ================= EDIT ================= */
    public function edit($id)
    {
        $trip = Trip::findOrFail($id);
        $entities = Entity::where('deleted', 0)->get();
        $devices = Device::where('deleted', 0)->get();

        return view('pages.trips.form',
            compact('trip', 'entities', 'devices')
        );
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        $trip->update([
            'device_id' => $request->device_id,
            'entity_id' => $request->entity_id,
            'vechicle_number' => $request->vechicle_number,
            'direction' => $request->direction,
            'weight' => $request->weight,
            'device_timestamp' => $request->device_timestamp,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);

        $trip->update([
            'deleted' => 1,
            'deleted_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}
