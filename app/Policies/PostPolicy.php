<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    // Check if the user is the owner of the post
    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }
}
