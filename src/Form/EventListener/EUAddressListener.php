<?php

declare(strict_types=1);

namespace App\Form\EventListener;

use App\Form\OrderAddressData;
use App\Service\Address\AddressCheckerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;

class EUAddressListener implements EventSubscriberInterface
{
    public function __construct(private readonly AddressCheckerInterface $addressChecker)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    public function onPreSetData(PreSetDataEvent $event): void
    {
        /** @var OrderAddressData $orderAddress */
        $orderAddress = $event->getData();
        $form = $event->getForm();

        if ($this->addressChecker->isEUAddress($orderAddress->getUserAddress())) {
            $form->add('userTaxNumber', TextType::class, [
                'label' => 'Steuernummer',
                'required' => false,
            ]);
        }
    }

    public function onPreSubmit(PreSubmitEvent $event): void
    {
        /** @var OrderAddressData $orderAddress */
        $orderAddress = $event->getData();
        $form = $event->getForm();

        if (!$orderAddress) {
            return;
        }

        if ($this->addressChecker->isEUAddress($orderAddress['userAddress'])) {
            $form->add('userTaxNumber', TextType::class, [
                'label' => 'Steuernummer',
                'required' => false,
            ]);
        } else {
            unset($orderAddress['userTaxNumber']);
            $event->setData($orderAddress);
        }
    }
}
