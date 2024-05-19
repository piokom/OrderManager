<?php

namespace App\Service;

use App\Entity\CustomerOrder;

interface CustomerOrderCollectorInterface
{
    public function collect(CustomerOrder $customerOrder): void;
}
