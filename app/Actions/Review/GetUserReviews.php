<?php

namespace App\Actions\Review;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetUserReviews
{
    public function handle(User $user): Collection
    {
        return Review::where('user_id', $user->id)->get();
    }
}
