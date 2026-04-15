<?php

declare(strict_types=1);

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $table = 'shop_addresses';

          protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'phone_number',
        'company',
        'country_code',
        'province_code',
        'province_name',
        'street',
        'city',
        'postcode',
    ];

    public function setCountryCodeAttribute(?string $value): void
    {
        if ($value === null) {
            $this->attributes['province_code'] = null;
        }

        $this->attributes['country_code'] = $value;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFullName(): string
    {
        return trim(sprintf('%s %s', $this->first_name ?? '', $this->last_name ?? ''));
    }
}
