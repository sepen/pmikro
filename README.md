# `pmikro`

**pmikro** is a minimal PHP framework for building simple, modular web pages using a lightweight, custom-built template engine. It’s fast, dependency-free, and based entirely on a single file: `libtemplate.php`.

Ideal for small web projects, content-driven sites, and static-file serving with some light dynamic behavior.

## Features

- **Custom Template Engine** with Django-inspired syntax
- Supports static templates (`.html`, `.xml`, `.css`, `.json`, `.txt`, `.md`, ...)
- Looping and inclusion using simple tags
- Clean substitution logic using regular expressions
- No dependencies, no config, no setup — just plain PHP

## When to Use pmikro

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

### Comments

```jinja
{# this is a comment and will be removed #}
```
Used for internal notes. Stripped from the final output.

### Variable substitution

```html
<h1>{{ title }}</h1>
<p>{{ message }}</p>
```
Variables are replaced from the data you pass to the renderer.

### Looping

```html
{% for navpanel %}
  <a href="{{ href }}">{{ label }}</a>
{% endfor navpanel %}
```
Loops over an array `navpanel`, injecting the inner block for each item. Each item is an associative array (like `['href' => '...', 'label' => '...']`).

## Docker Usage

You can build and run **pmikro** using Docker for easy local development.

### Build the Docker image

From the root of the project:
```bash
docker build -t pmikro .
```

### Run the container

```bash
docker run -p 8000:8000 pmikro
```
By default, this will start the PHP development server (`php -S`) to serve content from the pub/ directory on port `8000`.
> Note: This is for development only. The PHP built-in server is not suitable for production.

### Mount a local volume

To serve your own files or templates directly from your host machine into the container:

```bash
docker run -p 8000:8000 -v $(pwd)/pub:/pmikro/pub pmikro
```
- `$(pwd)/pub` is your local `pub/` directory
- `/pmikro/pub` is the directory inside the container where files are served
This lets you modify files live on your host and have them reflected immediately in the container.

### Test it

Once the container is running, visit:
```
http://localhost:8000
```
You should see your `index.php` or rendered template served from the `pub/` directory.

## License

This project is licensed under the terms of the [GNU General Public License v3.0 (GPLv3)](LICENSE).
