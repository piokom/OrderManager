<?php

namespace App\Service;

use App\Entity\CustomerOrder;
use App\Entity\OrderItem;

class PriceCollector implements CustomerOrderCollectorInterface
{
    public function collect(CustomerOrder $customerOrder): void
    {
        $totalPrice = 0;
        
        /** @var OrderItem $orderItem */
        foreach ($customerOrder->getOrderItems() as $orderItem) {
            $totalPrice += $orderItem->getPrice() * $orderItem->getQuantity();
        }
        
        $customerOrder->setTotalPrice($totalPrice);
    }
}