<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

/**
 * Tags follow the articles they describe: the panel roles (admin and
 * editor) create and rename them; only administrators delete a tag, which
 * removes it from every article.
 */
class TagPolicy
{
    /**
     * Determine whether the user can list tags in the panel.
     */
    public function viewAny(User $user): bool
    {
        return $user->canAccessAdminPanel();
    }

    /**
     * Determine whether the user can create tags.
     */
    public function create(User $user): bool
    {
        return $user->canAccessAdminPanel();
    }

    /**
     * Determine whether the user can rename the tag.
     */
    public function update(User $user, Tag $tag): bool
    {
        return $user->canAccessAdminPanel();
    }

    /**
     * Determine whether the user can delete the tag.
     */
    public function delete(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }
}
