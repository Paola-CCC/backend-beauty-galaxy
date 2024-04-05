<?php

namespace App\Controllers;

use App\Managers\CartItemsManager;
use App\Service\HttpRequest;

class CartItemsController 
{
    private $cartItemsManager = null;
    private $httpRequest ;

    public function __construct()
    {
        $this->cartItemsManager = new CartItemsManager('cartItems');
        $this->httpRequest = new HttpRequest();
    }


    public function getAllCartItems() 
    {
        return json_encode($this->cartItemsManager->findAll());

    }


    public function getById(?int $id) 
    {
        return json_encode($this->cartItemsManager->findById($id));

    }


    public function newCartItems() 
    {
        $data = $this->httpRequest->getBody();

        $dataInsert = [
            'user_id' => $data['user_id'],
            'totalPricing' => $data['totalPricing'],
            'quantity_chosen' => $data['quantity_chosen'],
            'status' => $data['status'],
            'productID_List' => $data['productID_List']
        ];

        return json_encode($this->cartItemsManager->add($dataInsert));

    }


    public function updateCartItems() 
    {
        $data = $this->httpRequest->getBody();

        $dataUpdate = [
            'id' => (int) $data['id'],
            'user_id' => (int) $data['user_id'],
            'totalPricing' => (int) $data['totalPricing'],
            'quantity_chosen' => (int) $data['quantity_chosen'],
            'status' => $data['status'],
            'productID_List' => $data['productID_List']
        ];

        return json_encode($this->cartItemsManager->update($dataUpdate));
    }


    public function deleteCartItems(?int $id) 
    {
        return json_encode($this->cartItemsManager->delete($id));
    }
}