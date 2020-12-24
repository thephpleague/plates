<?php

final class Product
{
    private $styleNumber;
    private $name;
    private $description;
    private $gender;
    private $variants;
    private $urlKey;

    /** @param ProductVariant[] $variants */
    public function __construct(string $styleNumber, string $name, string $description, Gender $gender, string $urlKey, array $variants) {
        $this->styleNumber = $styleNumber;
        $this->name = $name;
        $this->description = $description;
        $this->gender = $gender;
        $this->variants = $variants;
        $this->urlKey = $urlKey;
    }

    public function styleNumber(): string {
        return $this->styleNumber;
    }

    public function name(): string {
        return $this->name;
    }

    public function description(): string {
        return $this->description;
    }

    public function gender(): Gender {
        return $this->gender;
    }

    /** @return ProductVariant[] */
    public function variants(): array {
        return $this->variants;
    }

    public function urlKey(): string {
        return $this->urlKey;
    }
}

abstract class Gender {
    private $type;
    private function __construct(string $type) { $this->type = $type; }
    public static function mens() {
        return new GenderMens('mens');
    }
    public static function womens() {
        return new GenderWomens('womens');
    }
}
final class GenderMens extends Gender {}
final class GenderWomens extends Gender {}

final class ProductVariant
{
    private $sku;
    private $price;
    private $title;
    private $quantity;

    public function __construct(string $sku, int $price, string $title, int $quantity) {
        $this->sku = $sku;
        $this->price = $price;
        $this->title = $title;
        $this->quantity = $quantity;
    }

    public function sku(): string {
        return $this->sku;
    }

    public function price(): int {
        return $this->price;
    }

    public function title(): string {
        return $this->title;
    }

    public function quantity(): int {
        return $this->quantity;
    }

    public function outOfStock(): bool {
        return $this->quantity === 0;
    }
}

final class NavItem
{
    private $title;
    private $link;
    private $children;

    /** @param NavItem[] $children */
    public function __construct(string $title, ?string $link = null, array $children = []) {
        $this->title = $title;
        $this->link = $link;
        $this->children = $children;
    }

    public function title(): string {
        return $this->title;
    }

    public function link(): ?string {
        return $this->link;
    }

    public function children(): array {
        return $this->children;
    }
}

/** @return Product[] */
function listProducts(): array {
    return [
        new Product(
            '55502 123',
            'Yeezy 555',
            'This is the new yeezy, it is really great.',
            Gender::mens(),
            'yeezy-555',
            [
                new ProductVariant('55502 123|6', 100, 'US 6', 10),
                new ProductVariant('55502 123|7', 120, 'US 7', 2),
                new ProductVariant('55502 123|8', 105, 'US 8', 5),
            ]
        ),
        new Product(
            '55502 124',
            'Yeezy 556',
            'This is brand new yeezy, also fairly good.',
            Gender::womens(),
            'yeezy-556',
            [
                new ProductVariant('55502 124|6', 100, 'US 6', 10),
                new ProductVariant('55502 124|7', 120, 'US 7', 2),
                new ProductVariant('55502 124|8', 105, 'US 8', 5),
            ]
        ),
    ];
}

function findProductByUrlKey(string $urlKey): ?Product {
    foreach (listProducts() as $product) {
        if ($product->urlKey() === $urlKey) {
            return $product;
        }
    }
    return null;
}
