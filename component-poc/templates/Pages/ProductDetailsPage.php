<?php

namespace Templates\Pages;

use Templates\Layouts\DefaultLayout;
use function League\Plates\{p, e};

final class ProductDetailsPage
{
    /** @var string */
    private $productTitle;
    /** @var string */
    private $productDescription;
    /** @var \ProductVariant[] */
    private $variants;

    public static function fromProduct(\Product $product): self {
        $self = new self();
        $self->productTitle = $product->name();
        $self->productDescription = $product->description();
        $self->variants = $product->variants();
        return $self;
    }

    public function __invoke(): void {
        echo p(DefaultLayout::new(function() {
        ?>  <div class="container mx-auto">
                <?=p($this->ProductTitle())?>
                <p><?=e($this->productDescription)?></p>
                <form>
                    <?=p($this->ProductSelect())?>
                    <button type="submit">Add to Cart!</button>
                </form>
            </div> <?php
        }, 'Product Details: ' . $this->productTitle)->withCss(function() {
        ?>  <style>
                .container { max-width: 1200px; }
                .mx-auto { margin-left: auto; margin-right: auto; }
                * { font-family: arial; }
            </style> <?php
        }));
    }

    private function ProductTitle() {
        return function () {
            ?> <h1><?=e($this->productTitle)?></h1> <?php
        };
    }

    private function ProductSelect() {
        return function() {
        ?>  <select name="product-select">
                <?php foreach ($this->variants as $variant): ?>
                <option value="<?=$variant->sku()?>"><?=$variant->title()?> - <?=$variant->price()?></option>
                <?php endforeach; ?>
            </select> <?php
        };
    }
}
