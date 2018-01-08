<?php

use League\Plates;

describe('Layout Sections Extension', function() {
    it('allows default layout', function() {
        $plates = new Plates\Engine([
            'base_dir' => __DIR__ . '/fixtures/default-layout',
            'default_layout_path' => './_layout',
        ]);

        $html = <<<html
<div>
    <div>main</div>
</div>

html;
        expect($plates->render('main'))->equal($html);
    });
});
