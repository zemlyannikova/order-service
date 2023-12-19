<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class OrderAddressData
{
    #[Assert\NotBlank]
    private ?string $userName;
    #[Assert\NotBlank]
    private ?string $userAddress;
    #[Assert\NotBlank]
    private ?string $userPhone;
    #[Assert\NotBlank]
    private ?string $userEmail;
    private ?string $userTaxNumber;
    private bool $saveAddress = false;

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserAddress(): ?string
    {
        return $this->userAddress;
    }

    public function setUserAddress(?string $userAddress): self
    {
        $this->userAddress = $userAddress;

        return $this;
    }

    public function getUserPhone(): ?string
    {
        return $this->userPhone;
    }

    public function setUserPhone(?string $userPhone): self
    {
        $this->userPhone = $userPhone;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(?string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getUserTaxNumber(): ?string
    {
        return $this->userTaxNumber;
    }

    public function setUserTaxNumber(?string $userTaxNumber): self
    {
        $this->userTaxNumber = $userTaxNumber;

        return $this;
    }

    public function isSaveAddress(): bool
    {
        return $this->saveAddress;
    }

    public function setSaveAddress(bool $saveAddress): self
    {
        $this->saveAddress = $saveAddress;

        return $this;
    }
}
