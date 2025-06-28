<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CustomAuditor
{
    /**
     * Record audit log manually
     *
     * @param string $action
     * @param array $context
     * @param string|null $modelType
     * @param string|null $modelId
     * @return AuditLog
     */
    public static function record(string $action, array $context = [], ?string $modelType = null, ?string $modelId = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'ip_address' => request()->ip() ?? 'console',
        ]);
    }

    /**
     * Record model change audit
     *
     * @param string $action
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $context
     * @return AuditLog
     */
    public static function recordModelChange(string $action, $model, array $context = []): AuditLog
    {
        return self::record(
            $action,
            $context,
            get_class($model),
            $model->getKey()
        );
    }
}
