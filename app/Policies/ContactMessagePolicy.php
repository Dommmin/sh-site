<?php

namespace App\Policies;

use App\Models\ContactMessage;
use App\Models\User;

/**
 * Panel roles (admin and editor) read contact messages; only
 * administrators trigger a new delivery, delete a message or export the
 * list (personal data of every sender).
 */
class ContactMessagePolicy
{
    /**
     * Determine whether the user can list contact messages.
     */
    public function viewAny(User $user): bool
    {
        return $user->canAccessAdminPanel();
    }

    /**
     * Determine whether the user can read the message.
     */
    public function view(User $user, ContactMessage $contactMessage): bool
    {
        return $user->canAccessAdminPanel();
    }

    /**
     * Determine whether the user can queue another delivery attempt.
     */
    public function retry(User $user, ContactMessage $contactMessage): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the message.
     */
    public function delete(User $user, ContactMessage $contactMessage): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can export the filtered list as CSV.
     */
    public function export(User $user): bool
    {
        return $user->isAdmin();
    }
}
