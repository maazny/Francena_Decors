<?php

namespace App\Contracts;

use App\Models\BackupSchedule;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface BackupScheduleServiceInterface
 * @package App\Contracts
 */
interface BackupScheduleServiceInterface
{
    /**
     * Get schedules with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getSchedules(int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new backup schedule configuration.
     *
     * @param array $data
     * @return BackupSchedule
     */
    public function createSchedule(array $data): BackupSchedule;

    /**
     * Update backup schedule configuration.
     *
     * @param BackupSchedule $schedule
     * @param array $data
     * @return BackupSchedule
     */
    public function updateSchedule(BackupSchedule $schedule, array $data): BackupSchedule;

    /**
     * Delete a backup schedule.
     *
     * @param BackupSchedule $schedule
     * @return void
     */
    public function deleteSchedule(BackupSchedule $schedule): void;

    /**
     * Enable backup schedule.
     *
     * @param BackupSchedule $schedule
     * @return void
     */
    public function enableSchedule(BackupSchedule $schedule): void;

    /**
     * Disable backup schedule.
     *
     * @param BackupSchedule $schedule
     * @return void
     */
    public function disableSchedule(BackupSchedule $schedule): void;
}
