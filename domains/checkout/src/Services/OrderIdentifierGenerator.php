<?php

declare(strict_types=1);

namespace Modules\Checkout\Services;

use Modules\Checkout\Models\Order;
use Illuminate\Support\Str;

final class OrderIdentifierGenerator
{
    public function generateNumber(Order $order): string
    {
        return sprintf('SYL-%s-%06d', now()->format('Ymd'), $order->id);
    }

    public function generateToken(): string
    {
        return Str::lower(Str::random(32));
    }
}

