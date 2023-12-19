<?php

declare(strict_types=1);

namespace App\Service\Address;

class StubAddressChecker implements AddressCheckerInterface
{
    public function isEUAddress(?string $address): bool
    {
        return $address === 'EU';
    }
}
