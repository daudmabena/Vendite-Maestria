<?php

declare(strict_types=1);

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerGroup extends Model
{
    protected $table = 'shop_customer_groups';

    protected $fillable = [
        'code',
        'name',
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'customer_group_id');
    }
}
