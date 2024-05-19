<?php

namespace App\Controller;

use App\Entity\CustomerOrder;
use App\Entity\OrderItem;
use App\Repository\CustomerOrderRepository;
use App\Repository\ProductRepository;
use App\Service\CustomerOrderCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CustomerOrderController extends AbstractController
{
    private CustomerOrderCalculator $customerOrderCalculator;

    public function __construct(CustomerOrderCalculator $customerOrderCalculator)
    {
        $this->customerOrderCalculator = $customerOrderCalculator;
    }

    #[Route('/customer_order', name: 'create_customer_order', methods: ['POST'])]
    public function createCustomerOrder(Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['items']) || !is_array($data['items'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }
        
        $customerOrder = new CustomerOrder();
        $customerOrder->setCreatedAt(new \DateTime());
        
        foreach ($data['items'] as $item) {
            $product = $productRepository->find($item['productId']);
            
            if (!$product) {
                return $this->json(['error' => 'Product not found'], 404);
            }
            
            $orderItem = new OrderItem();
            $orderItem->setCustomerOrder($customerOrder);
            $orderItem->setProduct($product);
            $orderItem->setQuantity($item['quantity']);
            $orderItem->setPrice($product->getPrice());
            $orderItem->setVat($product->getPrice() * $product->getVatRate() / 100);
            
            $entityManager->persist($orderItem);
        }
        
        // calculate total price & vat
        $this->customerOrderCalculator->calculate($customerOrder);
        
        $entityManager->persist($customerOrder);
        $entityManager->flush();
        
        return $this->json($customerOrder);        
    }

    #[Route('/customer_order/{id}', name: 'get_customer_order', methods: ['GET'])]
    public function getCustomerOrder(int $id, CustomerOrderRepository $customerOrderRepository): JsonResponse
    {
        $customerOrder = $customerOrderRepository->find($id);
        
        if (!$customerOrder) {
            return $this->json(['error' => 'Customer order not found']);
        }
        
        return $this->json($customerOrder);        
    }
}
