<?php

namespace App\DTO;

class CategoryDTO
{
    public string $name;
    public ?array $subcategories;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->subcategories = $data['subcategories'] ?? null;
    }
}
