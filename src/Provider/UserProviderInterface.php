<?php

declare(strict_types=1);

namespace App\Provider;

use App\Entity\User;
use App\Exception\Unauthorised;

interface UserProviderInterface
{
    /**
     * @throws Unauthorised
     */
    public function getCurrentUser(): User;
}
