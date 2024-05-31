<?php

namespace App\Service;

use App\Entity\CustomerOrder;

class CustomerOrderCalculator
{
    private iterable $collectors;
    
    public function __construct(iterable $collectors)
    {
        $this->collectors = $collectors;
    }

    public function calculate(CustomerOrder $customerOrder): void
    {
        foreach ($this->collectors as $collector) {
            $collector->collect($customerOrder);
        }
    }
}
