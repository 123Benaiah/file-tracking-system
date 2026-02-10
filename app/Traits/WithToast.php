<?php

namespace App\Traits;

trait WithToast
{
    /**
     * Dispatch a success toast notification
     */
    public function toastSuccess(string $title, string $message = '', int $duration = 6000): void
    {
        $this->dispatch('toast', [
            'type' => 'success',
            'title' => $title,
            'message' => $message,
            'duration' => $duration,
        ]);
    }

    /**
     * Dispatch an error toast notification
     */
    public function toastError(string $title, string $message = '', int $duration = 7000): void
    {
        $this->dispatch('toast', [
            'type' => 'error',
            'title' => $title,
            'message' => $message,
            'duration' => $duration,
        ]);
    }

    /**
     * Dispatch a warning toast notification
     */
    public function toastWarning(string $title, string $message = '', int $duration = 6000): void
    {
        $this->dispatch('toast', [
            'type' => 'warning',
            'title' => $title,
            'message' => $message,
            'duration' => $duration,
        ]);
    }

    /**
     * Dispatch an info toast notification
     */
    public function toastInfo(string $title, string $message = '', int $duration = 6000): void
    {
        $this->dispatch('toast', [
            'type' => 'info',
            'title' => $title,
            'message' => $message,
            'duration' => $duration,
        ]);
    }

    /**
     * Dispatch a custom toast notification
     */
    public function toast(string $type, string $title, string $message = '', int $duration = 5000, ?array $action = null): void
    {
        $this->dispatch('toast', [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'duration' => $duration,
            'action' => $action,
        ]);
    }

    /**
     * Flash a toast to session (for use before redirects)
     */
    public function flashToast(string $type, string $title, string $message = '', int $duration = 6000): void
    {
        session()->flash('toast', [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'duration' => $duration,
        ]);
    }

    /**
     * Flash a success toast to session (for use before redirects)
     */
    public function flashSuccess(string $title, string $message = '', int $duration = 6000): void
    {
        $this->flashToast('success', $title, $message, $duration);
    }

    /**
     * Flash an error toast to session (for use before redirects)
     */
    public function flashError(string $title, string $message = '', int $duration = 7000): void
    {
        $this->flashToast('error', $title, $message, $duration);
    }

    /**
     * Flash a warning toast to session (for use before redirects)
     */
    public function flashWarning(string $title, string $message = '', int $duration = 6000): void
    {
        $this->flashToast('warning', $title, $message, $duration);
    }

    /**
     * Flash an info toast to session (for use before redirects)
     */
    public function flashInfo(string $title, string $message = '', int $duration = 6000): void
    {
        $this->flashToast('info', $title, $message, $duration);
    }
}
