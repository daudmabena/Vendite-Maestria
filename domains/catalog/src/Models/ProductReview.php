<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    protected $table = 'shop_product_reviews';

    protected $fillable = [
        'product_id',
        'customer_id',
        'rating',
        'title',
        'comment',
        'status',
        'accepted_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
            'rating' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

