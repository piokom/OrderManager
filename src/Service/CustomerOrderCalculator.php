<?php

namespace App\Service;

class CustomerOrderCalculator
{
    public function calculatePrice(array $items): array
    {
        $totalPrice = 0;
        $totalVat = 0;
        
        foreach ($items as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
            $totalVat += $item['price'] * $item['vatRate'] / 100 * $item['quantity'];
        }
        
        return [
          'totalPrice' => $totalPrice,
          'totalVat' => $totalVat,
          'total' => $totalPrice + $totalVat,  
        ];
    }
}
