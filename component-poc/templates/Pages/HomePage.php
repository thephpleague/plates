<?php

namespace Templates\Pages;

use Templates\Layouts\DefaultLayout;
use function League\Plates\p;

final class HomePage
{
    private $products;

    /** @param \Product[] $products */
    public function __construct(array $products) {
        $this->products = $products;
    }

    public function __invoke(): void {
        echo p(DefaultLayout::new(function() {
        ?>  <h1>Home Page</h1>
            <div>
                <p>Products</p>
                <?=p($this->ProductsList())?>
            </div> <?php
        }, 'Home Page'));
    }

    private function ProductsList() {
        return function(): void {
            if (!$this->products) {
                ?> <b>No products</b> <?php
                return;
            }

        ?>  <ul>
                <?php foreach($this->products as $product): ?>
                <li>
                    <a href="/products/<?=$product->urlKey()?>"><?=$product->name()?></a>
                </li>
                <?php endforeach; ?>
            </ul> <?php
        };
    }
}
