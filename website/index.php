<?php

/*
|--------------------------------------------------------------------------
| PHP settings
|--------------------------------------------------------------------------
*/

error_reporting(-1);
ini_set('display_errors', false);


/*
|--------------------------------------------------------------------------
| Include configuration file
|--------------------------------------------------------------------------
*/

if (is_file('config.json')) {
    $config = json_decode(file_get_contents('config.json'));
} else {
    die('Configuration file (config.json) not found.');
}


/*
|--------------------------------------------------------------------------
| Setup class loading
|--------------------------------------------------------------------------
*/

// Vendor classes
include $config->base_path . '/vendor/autoload.php';


/*
|--------------------------------------------------------------------------
| Setup error handling
|--------------------------------------------------------------------------
*/

$whoops = new \Whoops\Run;
$whoops->pushHandler(
    function ($exception, $inspector, $whoops) use ($config) {

        // Remove any previous output
        ob_get_level() and ob_end_clean();

        // Set response code
        http_response_code(500);

        // Display errors
        if ($config->show_errors) {
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
            $whoops->handleException($exception);
        }

        exit;
    }
);
$whoops->register();


/*
|--------------------------------------------------------------------------
| Create request object
|--------------------------------------------------------------------------
*/

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();


/*
|--------------------------------------------------------------------------
| Template template engine
|--------------------------------------------------------------------------
*/

// Create new plates engine
$plates = new \Plates\Engine($config->base_path . '/views', 'tpl');

// Load any additional extensions
$plates->loadExtension(new \Plates\Extension\Asset($config->base_path, true));
$plates->loadExtension(new \Plates\Extension\URI($request->getPathInfo()));


/*
|--------------------------------------------------------------------------
| Setup routing
|--------------------------------------------------------------------------
*/

// Create router
$router = new \Reinink\Roundabout\Router($request);

$router->get(
    '/([a-z-]*)',
    function ($page = 'home') use ($config, $plates) {

        // Set index page
        if ($page === '') {
            $page = 'introduction';
        }

        // Check if page exists
        if (!file_exists($config->base_path . '/pages/' . $page  . '.md')) {
            return false;
        }

        // Get page content
        $page = file_get_contents($config->base_path . '/pages/' . $page  . '.md');

        // Convert Markdown to HTML
        $html = \Michelf\MarkdownExtra::defaultTransform($page);

        // Create template
        $template = new \Plates\Template($plates);
        $template->page = $html;

        // Return rendered template
        return new \Symfony\Component\HttpFoundation\Response($template->render('template'));
    }
);

// Run router
if (!$response = $router->run()) {
    $response = new \Symfony\Component\HttpFoundation\Response('404: Page not found.', 404);
}

// Send response
$response->prepare($request);
$response->send();
