<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\TransitionNotAllowedException;
use App\Repository\OrderRepository;
use Symfony\Component\Workflow\WorkflowInterface;

class OrderTransitionsMaker
{
    public function __construct(
        private readonly WorkflowInterface $orderWorkflow,
        private readonly OrderRepository   $orderRepository,
    ) {
    }

    /**
     * @throws TransitionNotAllowedException
     */
    public function makeTransition(int $orderId, string $transitionName): void
    {
        $order = $this->orderRepository->getOrder($orderId);

        if (! $this->orderWorkflow->can($order, $transitionName)) {
            throw new TransitionNotAllowedException();
        }

        $this->orderWorkflow->apply($order, $transitionName);
        $this->orderRepository->saveOrder($order);
    }
}
