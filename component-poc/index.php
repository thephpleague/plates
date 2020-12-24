<?php

use League\Plates\ContextRegistry;
use Templates\Pages\HomePage;
use Templates\Pages\ProductDetailsPage;
use function League\Plates\p;
use Templates\TemplateContext;

foreach ([
    __DIR__ . '/plates.php',
    __DIR__ . '/model.php',
    __DIR__ . '/templates/TemplateContext.php',
    __DIR__ . '/templates/Layouts/DefaultLayout.php',
    __DIR__ . '/templates/Layouts/Nav.php',
    __DIR__ . '/templates/Pages/HomePage.php',
    __DIR__ . '/templates/Pages/ProductDetailsPage.php',
] as $file) {
    require_once $file;
}

function homePageController() {
    echo p(new HomePage(listProducts()));
}

function productDetailsPageController(string $productUrlKey) {
    $product = findProductByUrlKey($productUrlKey);
    if (!$product) {
        notFoundController();
        return;
    }

    echo p(ProductDetailsPage::fromProduct($product));
}

function notFoundController() {
    header('Content-Type: text/plain', true, 404);
    echo "not found!";
}

function main(): void {
    $requestUri = $_SERVER['REQUEST_URI'];
    ContextRegistry::self()->set((new TemplateContext())->withNavItems([
        new NavItem('Home', '/'),
        new NavItem('Acme'),
        new NavItem('Beta')
    ]));

    if ($requestUri === '/') {
        homePageController();
    } else if (strpos($requestUri, '/products/') === 0) {
        productDetailsPageController(substr($requestUri, strlen('/products/')));
    } else {
        notFoundController();
    }
}

main();
