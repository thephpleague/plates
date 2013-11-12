Building extensions
===================

Creating extensions couldn't be easier, and can really make Plates sing for your specific project. Start by creating a class with a `getFunctions()` method indicating which methods in that class are to be available within your templates as functions.

## Simple extensions example

~~~language-php
<?php

class ChangeCase implements \Plates\Extension\ExtensionInterface
{
    public $engine;
    public $template;

    public function getFunctions()
    {
        return array(
            'uppercase' => 'uppercaseString',
            'lowercase' => 'lowercaseString'
        );
    }

    public function uppercaseString($var)
    {
        return strtoupper($var);
    }

    public function lowercaseString($var)
    {
        return strtolower($var);
    }
}
~~~

To use this extension in your template, call the functions you've made available:

~~~language-php
<p>Hello, <?=$this->uppercase($this->firstname)?> <?=$this->lowercase($this->firstname)?>.</p>
~~~

## Single method extensions

Alternatively, you may choose to expose the entire extension object to the template using a single function. This can make your templates more legible and also reduce the chance of conflicts with other extensions.

~~~language-php
<?php

class ChangeCase implements \Plates\Extension\ExtensionInterface
{
    public $engine;
    public $template;

    public function getFunctions()
    {
        return array(
            'case' => 'getCaseObject'
        );
    }

    public function getCaseObject()
    {
        return $this;
    }

    public function upper($var)
    {
        return strtoupper($var);
    }

    public function lower($var)
    {
        return strtolower($var);
    }
}
~~~

To use this extension in your template, first call the primary function, then the secondary functions:

~~~language-php
<p>Hello, <?=$this->case()->upper($this->firstname)?> <?=$this->case()->lower($this->firstname)?>.</p>
~~~

## Loading extensions

Once you've created an extension, load it into the `Engine` object using the `loadExtension()` function.

~~~language-php
<?php

// Load custom extension
$plates->loadExtension(new \ChangeCase());
~~~