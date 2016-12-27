---
layout: default
permalink: extensions/uri/
title: URI extension
---

URI
===

The URI extension is designed to make URI checks within templates easier. The most common use is marking the current page in a menu as "selected". It only has one function, `uri()`, but can do a number of helpful tasks depending on the parameters passed to it.

## Installing the URI extension

The URI extension comes packaged with Plates but is not enabled by default, as it requires an extra parameter passed to it at instantiation.

~~~ php
// Load URI extension using global variable
$engine->loadExtension(new League\Plates\Extension\URI($_SERVER['PATH_INFO']));

// Load URI extension using a HttpFoundation's request object
$engine->loadExtension(new League\Plates\Extension\URI($request->getPathInfo()));
~~~

## URI example

~~~ php
<ul>
    <li <?=$this->uri('/', 'class="selected"')?>><a href="/">Home</a></li>
    <li <?=$this->uri('/about', 'class="selected"')?>><a href="/about">About</a></li>
    <li <?=$this->uri('/products', 'class="selected"')?>><a href="/products">Products</a></li>
    <li <?=$this->uri('/contact', 'class="selected"')?>><a href="/contact">Contact</a></li>
</ul>
~~~

## Using the URI extension

Get the whole URI.

~~~ php
<?=$this->uri()?>
~~~

Get a specified segment of the URI.

~~~ php
<?=$this->uri(1)?>
~~~

Check if a specific segment of the URI (first parameter) equals a given string (second parameter). Returns `true` on success or `false` on failure.

~~~ php
<?php if ($this->uri(1, 'home')): ?>
~~~

Check if a specific segment of the URI (first parameter) equals a given string (second parameter). Returns string (third parameter) on success or `false` on failure.

~~~ php
<?=$this->uri(1, 'home', 'success')?>
~~~

Check if a specific segment of the URI (first parameter) equals a given string (second parameter). Returns string (third parameter) on success or string (fourth parameter) on failure.

~~~ php
<?=$this->uri(1, 'home', 'success', 'fail')?>
~~~

Check if a regular expression string matches the current URI. Returns `true` on success or `false` on failure.

~~~ php
<?php if($this->uri('/home')): ?>
~~~

Check if a regular expression string (first parameter) matches the current URI. Returns string (second parameter) on success or `false` on failure.

~~~ php
<?=$this->uri('/home', 'success')?>
~~~

Check if a regular expression string (first parameter) matches the current URI. Returns string (second parameter) on success or string (third parameter) on failure.

~~~ php
<?=$this->uri('/home', 'success', 'fail')?>
~~~