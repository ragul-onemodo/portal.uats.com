<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\NotificationRule;
use App\Models\NotificationRuleRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Yajra\DataTables\Facades\DataTables;


class NotificationSettingsController extends Controller
{
    protected string $module = 'settings.notification';

    /**
     * List notification rules
     */
    public function index()
    {
        $this->pageData['pageTitle'] = 'Notification Settings';
        $this->pageData['rules'] = NotificationRule::with('recipients')->get();


        return $this->view('index', $this->pageData);
    }


    /**
     * Datatable endpoint
     */
    public function datatable(Request $request)
    {
        $query = NotificationRule::query()
            ->select(
                'notification_rules.id',
                'notification_rules.target_type',
                'notification_rules.target_id',
                'notification_rules.event',
                'notification_rules.channel',
                'notification_rules.is_active',


                'entities.name as entity_name',
                'devices.device_name as device_name',
            )

            ->leftJoin('entities', function ($join) {
                $join->on('entities.id', '=', 'notification_rules.target_id')
                    ->where('notification_rules.target_type', '=', 'entity');
            })
            ->leftJoin('devices', function ($join) {
                $join->on('devices.id', '=', 'notification_rules.target_id')
                    ->where('notification_rules.target_type', '=', 'device');
            })

            ->with('recipients');

        if ($request->filled('target_type')) {
            $query->where('notification_rules.target_type', $request->target_type);
        }

        if ($request->filled('event')) {
            $query->where('notification_rules.event', $request->event);
        }

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('scope', function ($row) {

                if ($row->target_type === 'entity') {
                    return '
            <strong>Entity</strong>
            <div class="text-muted small">
                ' . e($row->entity_name ?? '—') . '
            </div>';
                }

                if ($row->target_type === 'device') {
                    return '
            <strong>Device</strong>
            <div class="text-muted small">
                ' . e($row->device_name ?? '—') . '
            </div>';
                }

                return '<span class="text-muted">—</span>';
            })

            ->addColumn('event_label', function ($row) {
                if ($row->event) {
                    return '<span class="badge bg-secondary">' . e($row->event) . '</span>';
                }

                return '<span class="text-muted">All events</span>';
            })

            ->addColumn('channel_badge', function ($row) {
                return '<span class="badge bg-info text-dark">'
                    . strtoupper($row->channel) .
                    '</span>';
            })

            ->addColumn('recipients', function ($row) {
                if ($row->recipients->isEmpty()) {
                    return '<span class="text-muted">—</span>';
                }

                return $row->recipients
                    ->map(fn($r) => '📧 ' . e($r->recipient_value))
                    ->implode('<br>');
            })

            ->addColumn('status_badge', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Disabled</span>';
            })

            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-sm btn-primary btn-edit rounded-pill"
                        data-id="' . $row->id . '">
                    <i class="fas fa-edit"></i>
                </button>

                <button class="btn btn-sm btn-danger btn-delete rounded-pill"
                        data-id="' . $row->id . '">
                    <i class="fas fa-trash"></i>
                </button>
            ';
            })

            ->rawColumns([
                'scope',
                'event_label',
                'channel_badge',
                'recipients',
                'status_badge',
                'action',
            ])
            ->make(true);
    }


    public function create()
    {
        return $this->edit();
    }

    /**
     * Show create / edit form
     */
    public function edit(?int $id = null)
    {

        $this->pageData['entities'] = DB::table('entities')
            ->select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $this->pageData['rule'] = $id
            ? NotificationRule::with('recipients')->findOrFail($id)
            : null;

        return $this->view('edit', $this->pageData);
    }

    /**
     * Store or update notification rule
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $validated = $request->validate([
            'id' => 'nullable|integer|exists:notification_rules,id',

            'target_type' => 'required|string|in:entity,device,sensor',
            'target_id' => 'required|integer',

            'event' => 'nullable|string|max:255',

            'channel' => 'required|string|in:email',
            'is_active' => 'sometimes|boolean',

            'recipients' => 'required|array|min:1',
            'recipients.*.type' => 'required|string|in:email',
            'recipients.*.value' => 'required|email',
        ]);

        DB::transaction(function () use ($validated) {

            $rule = NotificationRule::updateOrCreate(
                ['id' => $validated['id'] ?? null],
                [
                    'target_type' => $validated['target_type'],
                    'target_id' => $validated['target_id'],
                    'event' => $validated['event'] ?? null,
                    'channel' => $validated['channel'],
                    'is_active' => $validated['is_active'] ?? true,
                ]
            );

            // Reset recipients (simplest + safest)
            $rule->recipients()->delete();

            foreach ($validated['recipients'] as $recipient) {
                NotificationRuleRecipient::create([
                    'notification_rule_id' => $rule->id,
                    'recipient_type' => $recipient['type'],
                    'recipient_value' => $recipient['value'],
                ]);
            }
        });

        return response()->json([
            'status' => true,
            'message' => 'Notification rule saved successfully',
        ]);
    }

    /**
     * Enable / disable a rule
     */
    public function toggle(int $id)
    {
        $rule = NotificationRule::findOrFail($id);

        $rule->update([
            'is_active' => !$rule->is_active,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Notification rule status updated',
            'active' => $rule->is_active,
        ]);
    }

    /**
     * Delete a rule
     */
    public function destroy(int $id)
    {
        DB::transaction(function () use ($id) {
            NotificationRuleRecipient::where('notification_rule_id', $id)->delete();
            NotificationRule::where('id', $id)->delete();
        });

        return response()->json([
            'status' => true,
            'message' => 'Notification rule deleted',
        ]);
    }
}
