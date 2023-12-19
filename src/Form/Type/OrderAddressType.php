<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\EventListener\EUAddressListener;
use App\Form\OrderAddressData;
use App\Service\Address\AddressCheckerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderAddressType extends AbstractType
{
    public function __construct(private readonly AddressCheckerInterface $addressChecker)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderAddressData::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userName', TextType::class, [
                'label' => 'Name',
            ])
            ->add('userAddress', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('userPhone', TextType::class, [
                'label' => 'Handy Nummer',
            ])
            ->add('userEmail', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('saveAddress', CheckboxType::class, [
                'label' => 'Adresse speichern',
                'required' => false,
            ])
            ->add('Weiter', SubmitType::class)
            ->addEventSubscriber(new EUAddressListener($this->addressChecker));
    }
}
