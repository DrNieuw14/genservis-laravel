<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($module, $action, $description, $targetUserId = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'target_user_id' => $targetUserId,
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}