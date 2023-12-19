<?php

declare(strict_types=1);

namespace App\Workflow;

class OrderStatus
{
    public const ShoppingCart = 'shopping_cart';
    public const DeliveryAddress = 'delivery_address';
    public const PurchaseSummary = 'purchase_summary';
    public const Ordered = 'ordered';
}
