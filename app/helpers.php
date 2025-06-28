<?php

use App\Services\CustomAuditor;

if (!function_exists('audit')) {
    /**
     * Record an audit log entry
     *
     * @param string $action
     * @param array $context
     * @param string|null $modelType
     * @param string|null $modelId
     * @return \App\Models\AuditLog
     */
    function audit(string $action, array $context = [], ?string $modelType = null, ?string $modelId = null)
    {
        return CustomAuditor::record($action, $context, $modelType, $modelId);
    }
}

if (!function_exists('audit_model')) {
    /**
     * Record an audit log entry for a model
     *
     * @param string $action
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $context
     * @return \App\Models\AuditLog
     */
    function audit_model(string $action, $model, array $context = [])
    {
        return CustomAuditor::recordModelChange($action, $model, $context);
    }
}
