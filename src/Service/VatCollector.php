<?php

namespace App\Service;

use App\Entity\CustomerOrder;
use App\Entity\OrderItem;
use App\Service\CustomerOrderCollectorInterface;

class VatCollector implements CustomerOrderCollectorInterface
{
    public function collect(CustomerOrder $customerOrder): void
    {
        $totalVat = 0;
        
        /** @var OrderItem $orderItem */
        foreach ($customerOrder->getOrderItems() as $orderItem) {
            $totalVat += $orderItem->getVat() * $orderItem->getQuantity();
        }
        $customerOrder->setTotalVat($totalVat);        
    }
}