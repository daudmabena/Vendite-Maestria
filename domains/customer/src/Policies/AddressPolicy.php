<?php

declare(strict_types=1);

namespace Modules\Customer\Policies;

use Modules\Customer\Models\Address;
use App\Models\User;

final class AddressPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->customer !== null;
    }

    public function view(User $user, Address $address): bool
    {
        return $user->customer !== null && $address->customer?->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->customer !== null;
    }

    public function update(User $user, Address $address): bool
    {
        return $user->customer !== null && $address->customer?->user_id === $user->id;
    }

    public function delete(User $user, Address $address): bool
    {
        return $user->customer !== null && $address->customer?->user_id === $user->id;
    }
}
