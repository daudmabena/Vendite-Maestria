<?php

declare(strict_types=1);

namespace Modules\Content\Repositories\Contracts;

use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;
use Modules\Content\Models\ContactMessage;

/**
 * @extends CrudRepositoryInterface<ContactMessage>
 */
interface ContactMessageRepositoryInterface extends CrudRepositoryInterface
{
}

