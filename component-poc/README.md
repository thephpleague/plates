# Plates Components Proof of Concept

## Why Component Systems are Great

### Composition + Flexibility

After spending a bit of time in my career using React, I grew to **really** love the idea of class/function components with a render method that just return their html. I feel like the component model with composition removes a lot of complexity in the library itself and gives the author a ton of freedom to structure their components however they want.

### IDE Friendly

The other amazing thing about using React is that it works very well with an IDE (when you use typescript) which is something in the recent years has been really important to me. I love being able to navigate code with method clicking, auto completion, and other IDE goodies, to where it's hard to go back to using files that don't support that natively. 

### Better Control Flow & Separation of Code

Whenever you need to reuse a template/component in a traditional templating system like blade/twig/plates, you need to make a new file, every time. With React, it's very common to have many small private components that can be reused or just help readability within one file.

What comes along with that is also the ability to make use of early returns/conditionals in ways that make your component easier to read and maintain. Let's look at an example:

I pulled this example from the symfony Easy Admin package. This partial is pretty straightforward, but look at how much nesting we need because of all the guard statements.

```twig
{% if app.session is not null and app.session.started %}
    {% set flash_messages = app.session.flashbag.all %}

    {% if flash_messages|length > 0 %}
        <div id="flash-messages">
            {% for label, messages in flash_messages %}
                {% for message in messages %}
                    <div class="alert alert-{{ label }}">
                        <button type="button" class="alert-close" onclick="this.closest('div').style.display='none'">&times;</button>

                        {{ message|trans|raw }}
                    </div>
                {% endfor %}
            {% endfor %}
        </div>
    {% endif %}
{% endif %}

```

## Introducing the New Component Proof of Concept System

In this directory, we have a simple web app that you can start with:

```
php -S localhost:8080 index.php
```

`index.php` is basically just the simple front/controller router that configures some basic things and is the entry point for each of the pages. It also manually requires the classes, but if this was hooked up with composer you'd just setup 

`model.php` is a collection of classes that would represent a scaled down model of ecommerce platform. This would be the core/domain of your application that likely would be persisted in some database.

`plates.php` is the minimum required code needed to actually implement component based templating in php. It's incredibly minimal, with all the magic happening in the `p` function which just stands for plates, but essentially it's used to render a component. We'll get into the rules of a component a bit later.

`templates` stores the actual template component classes.

### The rules of a Component (WIP)

A component is any callable that echo's content to the output buffer. That's it. The return value of the template is unused, so it's typically best to just declare a void return type to be explicit.

The component callable must be rendered with the `p` function which is responsible for sending in the Template Context. This is similar to context in React, where's it's just a global store that holds data that is registered before you try to render any components. You define your own class for the template context, name it whatever you like, but the idea is that this is a class that you can declare to give structure to any global data needed within your components.

To pass data into your component, just use a constructor.

A component can make private local components by simply having private methods that return functions that echo content.

### Example Component

Let's look at that flash messages example with a plates component.

```php
<?php

namespace Templates\Layout;

final class FlashMessages
{
    public function __invoke(TemplateContext $context): void {
        if (!$context->session && !$context->session->started) {
            return;
        }
        $flashMessages = $context->session->flashbag->all() ?? [];
        if (!$flashMessages) {
            return;
        }

    ?>  <div id="flash-messages">
            <?php foreach ($this->listMessages($context) as [$label, $message]): ?>
            <div class="alert alert-<?=$label?>">
                <button type="button" class="alert-close" onclick="this.closest('div').style.display='none'">&times;</button>
                <?=trans($message)?>
            </div>
            <?php endforeach; ?>
        </div> <?php
    }

    private function listMessages(TemplateContext $context): iterable {
        foreach ($context->app->session->flashbag->all() as [$label, $messages]) {
            foreach ($messages as $message) {
                yield [$label, $message];
            }
        }
    }
}

final class TemplateContext
{
    /** @var SomeSessionClass */
    public $session;
}
```

What's amazing in this example is that EVERYTHING in here is perfectly understandable by modern PHP editors and will absolutely support auto completion and method clicking. There's nothing fancy going on, this is just plain php with simple rules.

### Style Guide for Mixing PHP and HTML

There are quite a few ways you could mix your php + html, but I found a few tricks that I think help with readability.

#### Single Line Statements

Just use `?> content goes here <?php` indented naturally. 

```php
function Button(string $title) {
    return function() use ($title): void {
        ?> <button><?=$title?></button> <?php    
    };
}
```

#### Multi Line Statements

Align the first `?>` one indention back, then 2 spaces, then start your html, and keep that indentation consistent. End with the `<?php` on the same line as the closing element.

```php
function Button(string $title) {
    return function() use ($title): void {
    ?>  <div class="button-wrapper">
            <button><?=$title?></button> 
        </div> <?php    
    };
}
```

### What do you think?

I'm not entirely sure if this should be its own package separate from plates, or if this would be the natural direction to take the most popular php language backed templating language.

I'd imagine, there would be less extensions for things like folders/shared data, but rather, better integrations with frameworks like Symfony and Laravel where we setup scoped globals for creating urls from routes, asset management, session usage, form validation, etc etc. 

Either way, I'm open to feedback and hopefully this is something that can turn out well for the package.
