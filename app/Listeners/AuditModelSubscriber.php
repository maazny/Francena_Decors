<?php

namespace App\Listeners;

use App\Contracts\ActivityLogServiceInterface;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use App\Models\ActivityLog;
use Illuminate\Events\Dispatcher;

class AuditModelSubscriber
{
    protected ActivityLogServiceInterface $service;

    public function __construct(ActivityLogServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Handle Eloquent events.
     */
    public function handleEloquentEvent(string $eventName, array $data): void
    {
        if (!str_contains($eventName, ':')) {
            return;
        }

        [$event, $modelClass] = explode(':', $eventName);
        $event = trim($event);
        $modelClass = trim($modelClass);
        $model = $data[0] ?? null;

        if (!$model || $model instanceof ActivityLog) {
            return;
        }

        // Avoid logging temporary test runs or unmigrated models
        if (!class_exists($modelClass)) {
            return;
        }

        $actionMap = [
            'eloquent.created' => ActivityAction::CREATE,
            'eloquent.updated' => ActivityAction::UPDATE,
            'eloquent.deleted' => ActivityAction::DELETE,
            'eloquent.restored' => ActivityAction::RESTORE,
            'eloquent.forceDeleted' => ActivityAction::FORCE_DELETE,
        ];

        if (!isset($actionMap[$event])) {
            return;
        }

        $action = $actionMap[$event];
        $classBasename = class_basename($modelClass);
        $module = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $classBasename));

        // Skip internal/pivot models if they don't have primary key id
        if (!isset($model->id)) {
            return;
        }

        $description = sprintf(
            '%s: Model %s (ID: %s) was %s',
            ucfirst($action->value),
            $classBasename,
            $model->id,
            $action->value . 'd'
        );

        $oldValues = null;
        $newValues = null;

        if ($action === ActivityAction::CREATE) {
            $newValues = $this->sanitizeAttributes($model->getAttributes());
        } elseif ($action === ActivityAction::UPDATE) {
            $changes = $model->getChanges();
            if (empty($changes)) {
                return; // Nothing changed
            }
            $newValues = $this->sanitizeAttributes($changes);
            $oldValues = [];
            foreach (array_keys($changes) as $key) {
                $oldValues[$key] = $model->getOriginal($key);
            }
            $oldValues = $this->sanitizeAttributes($oldValues);
        } elseif ($action === ActivityAction::DELETE) {
            $oldValues = $this->sanitizeAttributes($model->getAttributes());
        }

        try {
            $this->service->log([
                'module' => $module,
                'action' => $action,
                'model_type' => $modelClass,
                'model_id' => $model->id,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'status' => ActivityStatus::SUCCESS,
            ]);
        } catch (\Throwable $e) {
            // Silently ignore to avoid breaking primary database operations
        }
    }

    /**
     * Sanitize sensitive attributes.
     */
    protected function sanitizeAttributes(array $attributes): array
    {
        $sensitiveFields = ['password', 'remember_token', 'token', 'secret', 'key', 'password_confirmation', 'api_key', 'auth_key'];
        foreach ($sensitiveFields as $field) {
            if (array_key_exists($field, $attributes)) {
                $attributes[$field] = '********';
            }
        }
        return $attributes;
    }

    /**
     * Register listeners.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen('eloquent.created:*', [self::class, 'handleEloquentEvent']);
        $events->listen('eloquent.updated:*', [self::class, 'handleEloquentEvent']);
        $events->listen('eloquent.deleted:*', [self::class, 'handleEloquentEvent']);
        $events->listen('eloquent.restored:*', [self::class, 'handleEloquentEvent']);
        $events->listen('eloquent.forceDeleted:*', [self::class, 'handleEloquentEvent']);
    }
}
