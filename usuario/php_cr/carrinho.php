<?php

class Product
{
    private int $id;
    private string $name;
    private float $price;
    private int $quantity;

    
    public function __construct(int $id = 0, string $name = '', float $price = 0.0, int $quantity = 0)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setPrice($price);
        $this->setQuantity($quantity);
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
