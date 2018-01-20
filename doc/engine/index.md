---
layout: default
permalink: engine/
title: The Engine
---

The Engine
==========

Plates uses a central object called the `Engine`, which is used to store the environment configuration, functions and extensions. It helps decouple your templates from the file system and other dependencies. For example, if you want to change the folder where your templates are stored, you can do so by simply changing the path in one location.

## Basic usage

~~~ php
// Create new Plates engine
$templates = new League\Plates\Engine('/path/to/templates');

// Add any any additional folders
$templates->addFolder('emails', '/path/to/emails');

// Load any additional extensions
$templates->loadExtension(new League\Plates\Extension\Asset('/path/to/public'));

// Create a new template
$template = $templates->make('emails::welcome');
~~~

## Dependency Injection

Plates is designed to be easily passed around your application and easily injected in your controllers or other application objects. Simply pass an instance of the `Engine` to any consuming objects, and then use either the `make()` method to create a new template, or the `render()` method to render it immediately. For example:

~~~ php
class Controller
{
    private $templates;

    public function __construct(League\Plates\Engine $templates)
    {
        $this->templates = $templates;
    }

    // Create a template object
    public function getIndex()
    {
        $template = $this->templates->make('home');

        return $template->render();
    }

    // Render a template directly
    public function getIndex()
    {
        return $this->templates->render('home');
    }
}
~~~