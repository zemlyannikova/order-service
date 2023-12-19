<?php

declare(strict_types=1);

namespace App\Service\Address;

interface AddressCheckerInterface
{
    public function isEUAddress(?string $address): bool;
}
