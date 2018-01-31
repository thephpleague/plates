---
layout: default
permalink: engine/extensions/
title: Extensions
---

Extensions
==========

Extensions in Plates are first class citizens. In fact, all of the functionality of plates is implemented via extensions. This enables very powerful customizations and features that are completely opt-in.

Every extension implements the `League\Plates\Extension` interface which is defined as:

<div class="filename">src/Extension.php</div>
```php
<?php

namespace League\Plates;

interface Extension {
    public function register(Engine $plates);
}
```

Extensions can do any/all of the following:

1. Configure the engine
2. Define services in the IoC container
3. Define engine methods

Extensions use a combination of these techniques to add features to plates.

## Case Study

Probably the easiest way to learn about the features of extensions is by a case study looking at how we would implement certain features into plates using extensions. These examples are taken from the actual Plates source code and already exist in documented extensions, but these will provide a great understanding to how the system works so that you can build your own great extensions!

### Changing the Escape Charset

Let's look at an extension that will simply use the `ISO-8859-1` charset when escaping data with the `$v()` func.

<div class="filename">src/Docs/ChangeEscapeCharset/ChangeEscapeCharsetExtension.php</div>
```php

<?php

namespace League\Plates\Docs\ChangeEscapeCharset;

use League\Plates;

final class ChangeEscapeCharsetExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $plates->addConfig([
            'escape_encoding' => 'ISO-8859-1' // defined in the RenderContextExtension
        ]);
    }
}
```

Luckily, the RenderContextExtension exposes a configuration flag for setting the charset.

### Adding Global Data

In this example, we want to create an extension that allows us to add global values into our templates.

We want to add a func like:

```
$plates->addGlobal('varName', 'value');
```

So that in any of our templates, we can access `$varName`.

The best way to implement something like this would be via a [Template Composer](#).

<div class="filename">src/Docs/GlobalData/GlobalDataExtension.php</div>
```php
<?php

namespace League\Plates\Docs\GlobalData;

use League\Plates;

final class GlobalDataExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $c = $plates->getContainer(); // 1. access the ioc container
        $c->add('globalData.globals', []); // 2. define an empty array in the container to store globals

        // 3. define a method for adding globals
        $plates->addMethods([
            'addGlobal' => function(Plates\Engine $e, $name, $value) {
                // 4. merge the new array with the globalData.globals definition
                $e->getContainer()->merge('globalData.globals', [$name => $value]);
            },
        ]);

        // 5. push a new composer onto the stack (will execute first)
        $plates->pushComposers(function($c) {
            return [
                // 6. create the new composer and pass in the globals array
                'globalData.assignGlobals' => assignGlobalsComposer($c->get('globalData.globals'))
            ];
        });
    }
}
```

<div class="filename">src/Docs/GlobalData/global-data.php</div>
```php
<?php

namespace League\Plates\Docs\GlobalData;

use League\Plates\Template;

function assignGlobalsComposer(array $globals) {
    return function(Template $t) use ($globals) {
        // 7. merge the globals with the original template data so that any template defined vars overwrite globals if conflict
        return $t->withData(array_merge(
            $globals,
            $t->data
        ));
    };
}
```

Now this introduced quite a few more concepts than the last example, so let's break down by point:

1. The IoC Container is just a simple service container similar to Laravel Service Container or Pimple. It stores service definitions lazily so that you can configure services and later retrieve them with minimal hassle.
2. We want want to store a simple value in the container. This will be stored as is and no processing will be done to it. It's just a simple array.
3. We are adding a new engine onto the method which will give users the ability to call `$plates->addGlobal('varName', 'value')`.
4. `$container->merge` is just a simple alias for retrieving the array from the container and then calling array_merge and then updating the said container.
5. The Plates Standard Extension defines composers which are just functions that transform the `League\Plates\Template` instance. They receive one argument of a Template and return a Template always.
6. We return a keyed array because this allows us to easily override other composers if we use the same name. The naming convention with values in the container are `extensionName.value`.
7. Here are implementing the composer which merges in the global data with the templates current data.
