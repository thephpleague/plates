---
layout: default
permalink: changelog/
title: Changelog
---

Changelog
=========

All notable changes to this project will be documented in this file.

{% for release in site.github.releases %}
## [{{ release.name }}]({{ release.html_url }}) - {{ release.published_at | date: "%Y-%m-%d" }}
{{ release.body | markdownify }}
{% endfor %}