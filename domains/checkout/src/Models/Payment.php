<?php

declare(strict_types=1);

namespace Modules\Checkout\Models;

use Modules\Checkout\Workflow\Enums\PaymentState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const STATE_AUTHORIZED = PaymentState::Authorized->value;

    public const STATE_CART = PaymentState::Cart->value;

    public const STATE_NEW = PaymentState::New->value;

    public const STATE_PROCESSING = PaymentState::Processing->value;

    public const STATE_COMPLETED = PaymentState::Completed->value;

    public const STATE_FAILED = PaymentState::Failed->value;

    public const STATE_CANCELLED = PaymentState::Cancelled->value;

    public const STATE_REFUNDED = PaymentState::Refunded->value;

    public const STATE_UNKNOWN = PaymentState::Unknown->value;

    protected $table = 'shop_payments';

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'currency_code',
        'amount',
        'state',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
