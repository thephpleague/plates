<?php

use League\Plates\Engine;

describe('Plates', function() {
    it('can render templates with layouts, sections, relative paths, absolute paths, and folders', function() {
        $plates = Engine::create(__DIR__ . '/fixtures/standard');
        $plates->addFolder('components', ['partials/components', '']);
        $res = $plates->render('main', [
            'name' => 'RJ & Emily',
            'sidebar_style' => 'simple',
        ]);
        $html = <<<html
<!DOCTYPE html>
<html>
    <head>
        <title>Profile - RJ & Emily</title>
                <meta charset="UTF-8"/>
            </head>
    <body>
        <div class="alert success">
            <p>Success!</p>
        </div>
        <div class="alert error">
            <p>Sorry...</p>
        </div>
        <h1>Hi RJ &amp; Emily</h1>
<div id="sidebar" class="simple"></div>
            <script type="text/javascript" id="main"></script>
    <script type="text/javascript" id="sidebar"></script>
            </body>
</html>

html;
        expect($res)->equal($html);
    });
});
