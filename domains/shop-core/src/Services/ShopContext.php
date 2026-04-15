<?php

declare(strict_types=1);

namespace Modules\ShopCore\Services;

use Modules\ShopCore\Models\Channel;

final class ShopContext
{
    private ?Channel $channel = null;

    private ?string $localeCode = null;

    public function setChannel(?Channel $channel): void
    {
        $this->channel = $channel;
    }

    public function channel(): ?Channel
    {
        return $this->channel;
    }

    public function setLocaleCode(?string $localeCode): void
    {
        $this->localeCode = $localeCode;
    }

    public function localeCode(): ?string
    {
        return $this->localeCode;
    }
}

