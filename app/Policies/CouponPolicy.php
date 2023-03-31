<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Coupon;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouponPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Coupon $coupon)
    {
        return $user->canAccessFilament() && !$coupon->orders()->count();
    }
}
