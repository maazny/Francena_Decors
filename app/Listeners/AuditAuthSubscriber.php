<?php

namespace App\Listeners;

use App\Contracts\ActivityLogServiceInterface;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Events\Dispatcher;

class AuditAuthSubscriber
{
    protected ActivityLogServiceInterface $service;

    public function __construct(ActivityLogServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Handle Login.
     */
    public function handleLogin(Login $event): void
    {
        $this->service->log([
            'user_id' => $event->user->id,
            'module' => 'authentication',
            'action' => ActivityAction::LOGIN,
            'description' => "User {$event->user->name} logged in successfully.",
            'status' => ActivityStatus::SUCCESS,
        ]);
    }

    /**
     * Handle Logout.
     */
    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            $this->service->log([
                'user_id' => $event->user->id,
                'module' => 'authentication',
                'action' => ActivityAction::LOGOUT,
                'description' => "User {$event->user->name} logged out.",
                'status' => ActivityStatus::SUCCESS,
            ]);
        }
    }

    /**
     * Handle Failed Login.
     */
    public function handleFailed(Failed $event): void
    {
        $email = $event->credentials['email'] ?? 'Unknown';
        $this->service->log([
            'module' => 'authentication',
            'action' => ActivityAction::LOGIN,
            'description' => "Failed login attempt for account email: {$email}.",
            'status' => ActivityStatus::FAILED,
        ]);
    }

    /**
     * Handle Password Reset.
     */
    public function handlePasswordReset(PasswordReset $event): void
    {
        $this->service->log([
            'user_id' => $event->user->id,
            'module' => 'authentication',
            'action' => ActivityAction::SETTINGS_UPDATE,
            'description' => "Password reset completed for user {$event->user->name}.",
            'status' => ActivityStatus::SUCCESS,
        ]);
    }

    /**
     * Register listeners.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Login::class, [self::class, 'handleLogin']);
        $events->listen(Logout::class, [self::class, 'handleLogout']);
        $events->listen(Failed::class, [self::class, 'handleFailed']);
        $events->listen(PasswordReset::class, [self::class, 'handlePasswordReset']);
    }
}
