<?php

declare(strict_types=1);

namespace App\Workflow;

class OrderTransition
{
    public const ConfirmCart = 'confirm_cart';
    public const ConfirmAddress = 'confirm_address';
    public const FinaliseOrder = 'finalise_order';
    public const RecheckAddress = 'recheck_address';
}
