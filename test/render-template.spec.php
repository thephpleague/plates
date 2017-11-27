<?php

use League\Plates\{
    RenderTemplate\PlatesRenderTemplate,
    Template,
    Content\StringContent,
    Content\CollectionContent,
    Content\LazyContent
};

describe('PlatesRenderTemplate', function() {
    it('it renders a templates content', function() {
        $rt = new PlatesRenderTemplate();
        $tpl = new Template('', [], [], new StringContent('abc'));

        expect($rt->renderTemplate($tpl))->equal('abc');
    });
    it('it renders nested content', function() {
        $layout = new Template('layout');
        $main = new Template('main');
        $main = $main->withContent(new StringContent('b'));
        $layout = $layout->withContent(new CollectionContent([
            new StringContent('a'),
            LazyContent::fromTemplate($main),
            new StringContent('c')
        ]));

        $main = $main->withContext([
            'layout' => $layout
        ]);

        $rt = new PlatesRenderTemplate();
        expect($rt->renderTemplate($main))->equal('abc');
    });
});
