<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Order;
use App\Entity\User;
use App\Form\OrderAddressData;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Service\Address\AddressCheckerInterface;
use App\Service\OrderAddressService;
use Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Symfony\Component\VarDumper\Dumper\esc;

class OrderAddressServiceTest extends TestCase
{
    /**
     * @var MockObject&OrderRepository
     */
    private $orderRepository;

    /**
     * @var MockObject&UserRepository
     */
    private $userRepository;

    /**
     * @var AddressCheckerInterface&MockObject
     */
    private $addressChecker;

    private OrderAddressService $orderAddressService;

    public function setUp(): void
    {
        $this->orderRepository = self::createMock(OrderRepository::class);
        $this->userRepository = self::createMock(UserRepository::class);
        $this->addressChecker = self::createMock(AddressCheckerInterface::class);

        $this->orderAddressService = new OrderAddressService(
            $this->orderRepository,
            $this->userRepository,
            $this->addressChecker,
        );
    }

    /**
     * @dataProvider getOrderAddressFormDataData
     */
    public function testGetOrderAddressFormData(
        bool $orderHasData,
        bool $userHasData,
        ?string $expectedData,
        bool $expectedSaveAddress,
    ): void {
        $user = new User();
        if ($userHasData) {
            $user->setName('userName')
                ->setAddress('userAddress')
                ->setEmail('userEmail')
                ->setPhone('userPhone')
                ->setTaxNumber('userTaxNumber');
        }

        $order = new Order($user);
        if ($orderHasData) {
            $order->setUserName('orderName')
                ->setUserAddress('orderAddress')
                ->setUserEmail('orderEmail')
                ->setUserPhone('orderPhone')
                ->setUserTaxNumber('orderTaxNumber');
        }

        $this->orderRepository->method('getOrder')
            ->willReturn($order);

        $orderAddressData = $this->orderAddressService->getOrderAddressFormData(5);
        self::assertEquals($expectedData ? $expectedData.'Name' : null, $orderAddressData->getUserName());
        self::assertEquals($expectedData ? $expectedData.'Address' : null, $orderAddressData->getUserAddress());
        self::assertEquals($expectedData ? $expectedData.'Email' : null, $orderAddressData->getUserEmail());
        self::assertEquals($expectedData ? $expectedData.'Phone' : null, $orderAddressData->getUserPhone());
        self::assertEquals($expectedData ? $expectedData.'TaxNumber' : null, $orderAddressData->getUserTaxNumber());
        self::assertEquals($expectedSaveAddress, $orderAddressData->isSaveAddress());
    }

    /**
     * @dataProvider isOrderAddressValidData
     */
    public function testIsOrderAddressValid(bool $isEUAddress, ?string $taxNumber, bool $expectedResult): void
    {
        $orderAddressData = (new OrderAddressData())
            ->setUserTaxNumber($taxNumber);

        $this->addressChecker->method('isEUAddress')
            ->willReturn($isEUAddress);

        $result = $this->orderAddressService->isOrderAddressValid($orderAddressData);
        self::assertEquals($expectedResult, $result);
    }

    /**
     * @dataProvider saveOrderAddressFormData
     */
    public function testSaveOrderAddressForm(bool $isSaveAddress): void
    {
        $user = new User();
        $order = new Order($user);
        $this->orderRepository->method('getOrder')
            ->willReturn($order);

        $expectedOrder = clone $order;
        $expectedOrder->setUserName('name')
            ->setUserAddress('address')
            ->setUserPhone('phone')
            ->setUserEmail('email')
            ->setUserTaxNumber('tax');

        $this->orderRepository->expects($this->once())
            ->method('saveOrder')
            ->with($expectedOrder);

        $expectedUser = clone $user;
        $expectedUser->setName($isSaveAddress ? 'name' : null)
            ->setAddress($isSaveAddress ? 'address' : null)
            ->setPhone($isSaveAddress ? 'phone': null)
            ->setEmail($isSaveAddress ? 'email' : null)
            ->setTaxNumber($isSaveAddress ? 'tax': null);

        $this->userRepository->expects($this->once())
            ->method('saveUser')
            ->with($expectedUser);

        $orderAddressData = (new OrderAddressData())
            ->setUserName('name')
            ->setUserAddress('address')
            ->setUserPhone('phone')
            ->setUserEmail('email')
            ->setUserTaxNumber('tax')
            ->setSaveAddress($isSaveAddress);
        $this->orderAddressService->saveOrderAddressForm(7, $orderAddressData);
    }

    public static function getOrderAddressFormDataData(): Generator
    {
        yield [
            'orderHasData' => false,
            'userHasData' => false,
            'expectedData' => null,
            'expectedSaveAddress' => false,
        ];

        yield [
            'orderHasData' => true,
            'userHasData' => false,
            'expectedData' => 'order',
            'expectedSaveAddress' => false,
        ];

        yield [
            'orderHasData' => false,
            'userHasData' => true,
            'expectedData' => 'user',
            'expectedSaveAddress' => true,
        ];

        yield [
            'orderHasData' => true,
            'userHasData' => true,
            'expectedData' => 'order',
            'expectedSaveAddress' => true,
        ];
    }

    public static function isOrderAddressValidData(): Generator
    {
        yield [
            'isEUAddress' => false,
            'taxNumber' => null,
            'expectedResult' => true,
        ];

        yield [
            'isEUAddress' => true,
            'taxNumber' => null,
            'expectedResult' => false,
        ];

        yield [
            'isEUAddress' => false,
            'taxNumber' => 'number',
            'expectedResult' => true,
        ];

        yield [
            'isEUAddress' => true,
            'taxNumber' => 'number',
            'expectedResult' => true,
        ];
    }

    public static function saveOrderAddressFormData(): Generator
    {
        yield [
            'isSaveAddress' => false,
        ];

        yield [
            'isSaveAddress' => true,
        ];
    }
}
