<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxonTranslation extends Model
{
    protected $table = 'shop_taxon_translations';

    protected $fillable = [
        'taxon_id',
        'locale',
        'name',
        'slug',
        'description',
    ];

    public function taxon(): BelongsTo
    {
        return $this->belongsTo(Taxon::class);
    }
}
