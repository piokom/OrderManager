<?php

namespace App\Service\Integration;

use App\Entity\CustomerOrder;
use App\Entity\OrderItem;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private HttpClientInterface $httpClient;
    private string $apiUrl;

    public function __construct(HttpClientInterface $httpClient, string $apiUrl)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }

    public function sendCustomerOrder(CustomerOrder $customerOrder): bool
    {        
        /** @var OrderItem[] $orderItems */
        $orderItems = $customerOrder->getOrderItems()->toArray();
        
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->apiUrl,
                [
                    'products' => array_walk($orderItems, fn(OrderItem $orderItem) => $orderItem->getProduct()->getId()),
                    'price' => $customerOrder->getTotalPrice(),
                ],
            );

            if (200 === $response->getStatusCode()) {
                return true;
            }
            
            return false;
            
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }
}