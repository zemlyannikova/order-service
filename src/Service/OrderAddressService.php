<?php

declare(strict_types=1);

namespace App\Service;

use App\Form\OrderAddressData;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Service\Address\AddressCheckerInterface;

class OrderAddressService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly UserRepository $userRepository,
        private readonly AddressCheckerInterface $addressChecker,
    ) {
    }

    public function getOrderAddressFormData(int $orderId): OrderAddressData
    {
        $order = $this->orderRepository->getOrder($orderId);
        $user = $order->getUser();
        $orderAddressData = new OrderAddressData();

        if (!empty($order->getUserName()) || !empty($order->getUserAddress()) || !empty($order->getUserPhone())
            || !empty($order->getUserEmail())
        ) {
            $orderAddressData->setUserName($order->getUserName())
                ->setUserAddress($order->getUserAddress())
                ->setUserPhone($order->getUserPhone())
                ->setUserEmail($order->getUserEmail())
                ->setUserTaxNumber($order->getUserTaxNumber());
        } else {
            $orderAddressData->setUserName($user->getName())
                ->setUserAddress($user->getAddress())
                ->setUserPhone($user->getPhone())
                ->setUserEmail($user->getEmail())
                ->setUserTaxNumber($user->getTaxNumber());
        }

        $orderAddressData->setSaveAddress(!empty($user->getName()));

        return $orderAddressData;
    }

    public function isOrderAddressValid(OrderAddressData $orderAddressData): bool
    {
        return !($this->addressChecker->isEUAddress($orderAddressData->getUserAddress())
            && empty($orderAddressData->getUserTaxNumber()));
    }

    public function saveOrderAddressForm(int $orderId, OrderAddressData $orderAddressData): void
    {
        $order = $this->orderRepository->getOrder($orderId);

        $order->setUserName($orderAddressData->getUserName())
            ->setUserAddress($orderAddressData->getUserAddress())
            ->setUserPhone($orderAddressData->getUserPhone())
            ->setUserEmail($orderAddressData->getUserEmail())
            ->setUserTaxNumber($orderAddressData->getUserTaxNumber());
        $this->orderRepository->saveOrder($order);

        $user = $order->getUser();
        if ($orderAddressData->isSaveAddress()) {
            $user->setName($orderAddressData->getUserName())
                ->setAddress($orderAddressData->getUserAddress())
                ->setPhone($orderAddressData->getUserPhone())
                ->setEmail($orderAddressData->getUserEmail())
                ->setTaxNumber($orderAddressData->getUserTaxNumber());
        } else {
            $user->setName(null)
                ->setAddress(null)
                ->setPhone(null)
                ->setEmail(null)
                ->setTaxNumber(null);
        }

        $this->userRepository->saveUser($user);
    }
}
