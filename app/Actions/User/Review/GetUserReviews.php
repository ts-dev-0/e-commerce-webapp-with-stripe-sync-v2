<?php

namespace App\Actions\User\Review;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetUserReviews
{
    public function handle(User $user): Collection
    {
        return $user->reviews()->latest()->get();
    }
}
