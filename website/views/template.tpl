<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Plates</title>
    <link rel="stylesheet" href="<?=$this->asset('/css/all.css')?>" />
</head>

<body>

<header>
    <a class="logo" href="/">
        <img src="/img/logo.png" alt="Plates">
    </a>
    <menu>
        <ul>
            <li <?=$this->uri('/', 'class="selected"')?>><a href="/">Introduction</a></li>
            <li <?=$this->uri('/simple-example', 'class="selected"')?>><a href="/simple-example">Simple example</a></li>
        </ul>
        <h2>The Engine</h2>
        <ul>
            <li <?=$this->uri('/getting-started', 'class="selected"')?>><a href="/getting-started">Getting started</a></li>
            <li <?=$this->uri('/file-extensions', 'class="selected"')?>><a href="/file-extensions">File extensions</a></li>
            <li <?=$this->uri('/folders', 'class="selected"')?>><a href="/folders">Folders</a></li>
        </ul>
        <h2>Templates</h2>
        <ul>
            <li <?=$this->uri('/variables', 'class="selected"')?>><a href="/variables">Variables</a></li>
            <li <?=$this->uri('/nesting', 'class="selected"')?>><a href="/nesting">Nesting</a></li>
            <li <?=$this->uri('/layouts', 'class="selected"')?>><a href="/layouts">Layouts</a></li>
            <li <?=$this->uri('/sections', 'class="selected"')?>><a href="/sections">Sections</a></li>
            <li <?=$this->uri('/inheritance', 'class="selected"')?>><a href="/inheritance">Inheritance</a></li>
            <li <?=$this->uri('/syntax', 'class="selected"')?>><a href="/syntax">Syntax</a></li>
        </ul>
        <h2>Extensions</h2>
        <ul>
            <li <?=$this->uri('/building-extensions', 'class="selected"')?>><a href="/building-extensions">How to build</a></li>
            <li <?=$this->uri('/escape', 'class="selected"')?>><a href="/escape">Escape</a></li>
            <li <?=$this->uri('/batch', 'class="selected"')?>><a href="/batch">Batch</a></li>
            <li <?=$this->uri('/uri', 'class="selected"')?>><a href="/uri">URI</a></li>
            <li <?=$this->uri('/asset', 'class="selected"')?>><a href="/asset">Asset</a></li>
        </ul>
    </menu>
</header>

<article>
    <?=$this->page?>
</article>

<script src="<?=$this->asset('/js/prism.js')?>"></script>

</body>
</html>