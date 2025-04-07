# pmikro

**pmikro** is a minimal PHP framework for building simple, modular web pages using a lightweight, custom-built template engine. It’s fast, dependency-free, and based entirely on a single file: `libtemplate.php`.

Ideal for small web projects, content-driven sites, and static-file serving with some light dynamic behavior.

## Features

- **Custom Template Engine** with Django-inspired syntax
- Supports static templates (`.html`, `.xml`, `.css`, `.json`, `.txt`, `.md`, ...)
- Looping and inclusion using simple tags
- Clean substitution logic using regular expressions
- No dependencies, no config, no setup — just plain PHP

## When to Use pmikro

- Small websites and project pages
- Static content rendering with light logic
- Internal dashboards or admin panels
- Prototypes and mockups

---

## Template Syntax

### Include files

```jinja
{% include 'header.template.html' %}
```
Includes another template file. Path is relative to your template root.

### Comments

```jinja
{# this is a comment and will be removed #}
```
Used for internal notes. Stripped from the final output.

### Variable substitution

```html
<h1>{{ title }}</h1>
<p>{{ message }}</p>
```
Variables are replaced from the data you pass to the renderer.

### Looping
```html
{% for navpanel %}
  <a href="{{ href }}">{{ label }}</a>
{% endfor navpanel %}
```
Loops over an array `navpanel`, injecting the inner block for each item. Each item is an associative array (like `['href' => '...', 'label' => '...']`).


## Configuration

Apache2 example with `mod_rewrite`:
```
<VirtualHost *:80>
  ServerName pmikro
  DocumentRoot "/var/www/vhosts/pmikro/pub"
  CustomLog ${APACHE_LOG_DIR}/pmikro.access.log Combined
  ErrorLog ${APACHE_LOG_DIR}/pmikro.error.log
  <Directory "/var/www/vhosts/pmikro/pub">
    AllowOverride All
    Order Deny,Allow
    Allow from All
  </Directory>
</VirtualHost>
```
