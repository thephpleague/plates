+++
title = "Asset"
[menu.main]
parent = "extensions"
weight = 1
+++

The asset extension can be used to quickly create "cache busted" asset URLs in your templates. This is particularly helpful for aggressively cached files that can potentially change in the future, such as CSS files, JavaScript files and images. It works by appending the timestamp of the file's last update to its URL. For example, `/css/all.css` becomes `/css/all.1373577602.css`. As long as the file does not change, the timestamp remains the same and caching occurs. However, if the file is changed, a new URL is automatically generated with a new timestamp, and visitors receive the new file.

## Installing the asset extension

The asset extension comes packaged with Plates but is not enabled by default, as it requires extra parameters passed to it at instantiation.

~~~ php
// Load asset extension
$engine->loadExtension(new League\Plates\Extension\Asset('/path/to/public/assets/', true));
~~~

The first constructor parameter is the file system path of the assets directory. The second is an optional `boolean` parameter that if set to true uses the filename caching method (ie. `file.1373577602.css`) instead of the default query string method (ie. `file.css?v=1373577602`).

## Filename caching

To make filename caching work, some URL rewriting is required:

### Apache example
~~~ php
<IfModule mod_rewrite.c>
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.+)\.(\d+)\.(js|css|png|jpg|gif)$ $1.$3 [L]
</IfModule>
~~~

### Nginx example

~~~ php
location ~* (.+)\.(?:\d+)\.(js|css|png|jpg|jpeg|gif)$ {
   try_files $uri $1.$2;
}
~~~

## Using the asset extension

~~~ php
<html>
<head>
    <title>Asset Extension Example</title>
    <link rel="stylesheet" href="<?=$this->asset('/css/all.css')?>" />
</head>

<body>

<img src="<?=$this->asset('/img/logo.png')?>">

</body>
</html>
~~~