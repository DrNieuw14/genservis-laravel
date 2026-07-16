<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->search) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    });

            });

        }

        if ($request->module) {
            $query->where('module', $request->module);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(25)->withQueryString();

        $modules = ActivityLog::select('module')->distinct()->orderBy('module')->pluck('module');

        $totalLogs = ActivityLog::count();

        $todayLogs = ActivityLog::whereDate('created_at', today())->count();

        $distinctUsers = ActivityLog::distinct('user_id')->count('user_id');

        $mostActiveModule = ActivityLog::select('module')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('module')
            ->orderByDesc('total')
            ->first()?->module ?? '-';

        return view('admin.activity-logs.index', compact(
            'logs',
            'modules',
            'totalLogs',
            'todayLogs',
            'distinctUsers',
            'mostActiveModule'
        ));
    }
}
