<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class ProductDTO
{
    public string $name;
    public float $price;
    public string $description;
    public bool $available;
    public ?UploadedFile $image;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->price = $data['price'];
        $this->description = $data['description'];
        $this->available = $data['available'];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->image = $data['image'];
        }
    }
}
