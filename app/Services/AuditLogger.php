<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function log($action, $module, $model = null, $details = null)
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(), // Can be null for system actions or failed logins if handled carefully
                'action' => $action,
                'module' => $module,
                'model_type' => $model ? get_class($model) : null,
                'model_id' => $model ? $model->id : null,
                'details' => is_array($details) ? json_encode($details) : $details,
                'ip_address' => Request::ip()
            ]);
        } catch (\Exception $e) {
            // Silently fail or log to system log to not break application flow
            \Illuminate\Support\Facades\Log::error('Audit Log Error: ' . $e->getMessage());
        }
    }
}
