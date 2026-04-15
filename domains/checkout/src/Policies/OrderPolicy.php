<?php

declare(strict_types=1);

namespace Modules\Checkout\Policies;

use Modules\Checkout\Models\Order;
use App\Models\User;

final class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->customer !== null;
    }

    public function view(User $user, Order $order): bool
    {
        return $user->customer !== null && $order->customer?->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->customer !== null;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->customer !== null && $order->customer?->user_id === $user->id;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->customer !== null && $order->customer?->user_id === $user->id;
    }
}
