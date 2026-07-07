<?php

namespace App\Services;

use App\Contracts\BackupScheduleServiceInterface;
use App\Models\BackupSchedule;
use App\Traits\LogsActivity;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class BackupScheduleService
 * @package App\Services
 */
class BackupScheduleService implements BackupScheduleServiceInterface
{
    use LogsActivity;

    /**
     * Get schedules with pagination.
     */
    public function getSchedules(int $perPage = 15): LengthAwarePaginator
    {
        return BackupSchedule::with('creator')->latest()->paginate($perPage);
    }

    /**
     * Create a new backup schedule configuration.
     */
    public function createSchedule(array $data): BackupSchedule
    {
        $schedule = BackupSchedule::create([
            'schedule_name' => $data['schedule_name'],
            'backup_type' => $data['backup_type'],
            'frequency' => $data['frequency'],
            'cron_expression' => $data['cron_expression'] ?? null,
            'storage_disk' => $data['storage_disk'] ?? config('backup.default_disk', 'local'),
            'retain_backups' => $data['retain_backups'] ?? 30,
            'is_active' => $data['is_active'] ?? true,
            'created_by' => auth()->id(),
            'notes' => $data['notes'] ?? null,
        ]);

        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::CREATE,
            'description' => "Backup schedule '{$schedule->schedule_name}' was configured.",
            'model_type' => BackupSchedule::class,
            'model_id' => $schedule->id,
        ]);

        return $schedule;
    }

    /**
     * Update backup schedule configuration.
     */
    public function updateSchedule(BackupSchedule $schedule, array $data): BackupSchedule
    {
        $schedule->update($data);

        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::UPDATE,
            'description' => "Backup schedule '{$schedule->schedule_name}' was modified.",
            'model_type' => BackupSchedule::class,
            'model_id' => $schedule->id,
        ]);

        return $schedule;
    }

    /**
     * Delete a backup schedule.
     */
    public function deleteSchedule(BackupSchedule $schedule): void
    {
        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::DELETE,
            'description' => "Backup schedule '{$schedule->schedule_name}' was deleted.",
            'model_type' => BackupSchedule::class,
            'model_id' => $schedule->id,
        ]);

        $schedule->delete();
    }

    /**
     * Enable backup schedule.
     */
    public function enableSchedule(BackupSchedule $schedule): void
    {
        $schedule->update(['is_active' => true]);

        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::UPDATE,
            'description' => "Backup schedule '{$schedule->schedule_name}' was enabled.",
            'model_type' => BackupSchedule::class,
            'model_id' => $schedule->id,
        ]);
    }

    /**
     * Disable backup schedule.
     */
    public function disableSchedule(BackupSchedule $schedule): void
    {
        $schedule->update(['is_active' => false]);

        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::UPDATE,
            'description' => "Backup schedule '{$schedule->schedule_name}' was disabled.",
            'model_type' => BackupSchedule::class,
            'model_id' => $schedule->id,
        ]);
    }
}
