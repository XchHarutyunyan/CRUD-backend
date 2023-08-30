<?php

namespace App\DTO;

class CartDTO
{
    public int $product_id;
    public int $quantity;

    public function __construct(array $data)
    {
        $this->product_id = $data['product_id'];
        $this->quantity = $data['quantity'];
    }
}
