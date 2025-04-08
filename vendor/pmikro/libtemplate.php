<?php

/**
 * Template engine for static templates (html, xml, css, json, txt, md, ...)
 * - Render operations are based on regular expressions
 * - Syntax:
 *   - Variables: {{ var }}
 *   - Loops: {% for var %}...{% endfor var %}
 *   - Includes: {% include 'file' %}
 *   - Comments: {# comment #}
 */
class Template
{
    protected string $templateName;
    protected string $templateType;
    protected string $templateFile;
    protected array $templateVars = [];
    protected string $templateOutput;

    public function __construct(string $name = 'index', string $type = 'html')
    {
        $this->templateName = $name;
        $this->templateType = $type;
        $this->templateFile = pmikro::$appDir . '/views/templates/' . $this->templateName . '.template.' . $this->templateType;

        if (!file_exists($this->templateFile)) {
            throw new RuntimeException('ERROR: Template does not exist: ' . $this->templateFile);
        }

        $this->templateOutput = file_get_contents($this->templateFile);
    }

    public function setVars(array $vars = []): void
    {
        $this->templateVars = array_merge($this->templateVars, $vars);
    }

    public function getVars(): array
    {
        return $this->templateVars;
    }

    public function printVars(): void
    {
        echo "\n<pre>\n";
        print_r($this->templateVars);
        echo "\n</pre>\n<br />\n";
    }

    public function getContents(): string
    {
        $this->templateOutput = $this->render($this->templateOutput, $this->templateVars);
        return $this->templateOutput;
    }

    public function setContents(string $contents): void
    {
        $this->templateOutput = $contents;
    }

    public function printContents(): void
    {
        $this->templateOutput = $this->render($this->templateOutput, $this->templateVars);
        echo $this->templateOutput;
    }

    public function render(string $contents, array $vars): string
    {
        $contents = $this->renderCommentRules($contents);
        $contents = $this->renderIncludeRules($contents);

        foreach ($vars as $key => $value) {
            if (is_array($value)) {
                $contents = $this->renderForRules($contents, [$key => $value]);
            } else {
                $pattern = '#{{\s*' . preg_quote($key, '#') . '\s*}}#';
                $replacement = $this->templateType === 'html'
                    ? (string)$value
                    : htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
                $contents = preg_replace($pattern, $replacement, $contents);
            }
        }

        return $contents;
    }

    private function renderForRules(string $contents, array $vars): string
    {
        foreach ($vars as $varKey => $varValue) {
            $regex = '#{% for ' . preg_quote($varKey, '#') . ' %}(.*?){% endfor ' . preg_quote($varKey, '#') . ' %}#s';
            preg_match_all($regex, $contents, $matches);

            foreach ($matches[1] as $matchContent) {
                $tmpContents = '';
                foreach ($varValue as $item) {
                    $tmpLine = $matchContent;
                    foreach ($item as $k => $v) {
                        $tmpLine = $this->render($tmpLine, [$k => $v]);
                    }
                    $tmpContents .= $tmpLine;
                }
                $contents = preg_replace($regex, $tmpContents, $contents, 1);
            }
        }

        return $contents;
    }

    private function renderIncludeRules(string $contents): string
    {
        $regex = '#{% include \'([^\']+)\' %}#';
        preg_match_all($regex, $contents, $matches);

        foreach ($matches[1] as $fileName) {
            $includePath = realpath(pmikro::$appDir . '/views/templates/' . $fileName);
            $baseDir = realpath(pmikro::$appDir . '/views/templates/');

            if ($includePath && str_starts_with($includePath, $baseDir) && file_exists($includePath)) {
                $fileContents = file_get_contents($includePath);
                $contents = str_replace("{% include '" . $fileName . "' %}", $fileContents, $contents);
            }
        }

        return $contents;
    }

    private function renderCommentRules(string $contents): string
    {
        $result = preg_replace('/\{\#.*?\#\}/s', '', $contents);
        return $result ?? '';
    }
}