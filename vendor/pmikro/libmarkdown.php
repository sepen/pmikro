<?php

/**
 * Class MarkdownParser
 *
 * This class provides a simple Markdown parser that converts a subset of Markdown syntax to HTML.
 * It supports headings, lists, bold, italic, links, and code blocks.
 */

class MarkdownParser
{
    protected int $debug = 0;
 
    public function __construct(int $debug = 0)
    {
        $this->debug = $debug;
    }

    /*
    * Parses a simple subset of Markdown syntax and converts it to HTML.
    * It supports headings, lists, bold, italic, links, and code blocks.
    *
    * @param string $text The Markdown text to parse
    * @return string The parsed HTML output
    */
    public function parse($text) {
        $lines = explode("\n", $text);
        $html = '';
        $inCodeBlock = false;
        $codeLang = '';
        $codeBuffer = [];

        foreach ($lines as $line) {
            $line = rtrim($line);

            // Check for fenced code block start/end
            if (preg_match('/^```(\w*)$/', $line, $matches)) {
                if (!$inCodeBlock) {
                    $inCodeBlock = true;
                    $codeLang = $matches[1] ?? '';
                    $codeBuffer = [];
                } else {
                    // End of code block
                    $class = $codeLang ? " class=\"language-$codeLang\"" : '';
                    $code = htmlspecialchars(implode("\n", $codeBuffer));
                    $html .= "<pre><code$class>$code</code></pre>\n";
                    $inCodeBlock = false;
                    $codeLang = '';
                }
                continue;
            }

            if ($inCodeBlock) {
                $codeBuffer[] = $line;
                continue;
            }

            // Headings
            if (preg_match('/^(#{1,6})\s+(.*)$/', $line, $matches)) {
                $level = strlen($matches[1]);
                $content = htmlspecialchars($matches[2]);
                $line = "<h$level>$content</h$level>";
            }
            // Unordered list
            elseif (preg_match('/^\-\s+(.*)$/', $line, $matches)) {
                $content = htmlspecialchars($matches[1]);
                $line = "<li>$content</li>";
            }

            // Inline: bold **text**
            $line = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $line);

            // Inline: italic *text*
            $line = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $line);

            // Inline: links [text](url)
            $line = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $line);

            // Inline: code `code`
            $line = preg_replace('/`(.*?)`/', '<code>$1</code>', $line);

            // Inline: --- or ___ for horizontal rule
            $line = preg_replace('/^[-_]{3,}$/', '<hr />', $line);

            // Inline: > for blockquote
            $line = preg_replace('/^>\s+(.*)$/', '<blockquote>$1</blockquote>', $line);

            // Add line to HTML output
            $html .= $line . "\n";

        }

        // Wrap <li> in <ul>
        $html = preg_replace_callback('/(<li>.*?<\/li>\n?)+/', function($matches) {
            return "<ul>\n" . $matches[0] . "</ul>\n";
        }, $html);

        return $html;
    }
 }

