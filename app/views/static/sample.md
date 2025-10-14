# User Guide

Page generated from `/views/static/userguide.md`

## Source code

Set the navigation panel
```php
$navpanel = require(self::$appDir.'/config/navpanel.php');
```

Set the contents of the page
```php
$markdown = file_get_contents(self::$appDir.'/views/static/userguide.md');
$mdparser = new MarkdownParser();
$contents = $mdparser->parse($markdown);
```

Create a new Template object and set the variables for the template
```php
$template = new Template('layout');
$template->setVars(['navpanel' => $navpanel]);
$template->setVars(['contents' => $contents]);
```

Render the template
```php
self::$appOutput = $template->getContents();
self::out();
```

---

### Examples

**Item** list example:
- item1
- item2
- item3

**In-line** preformated text: `here`
<br />

**Multi-line** preformated text:
```
line1 col2      col4 col5 col6      col8
line2 col2 col3           col6 col7
line3      col3      col5           col8
```

and many more ...