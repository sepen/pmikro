<?php

/**
 * Class Filesystem
 *
 * This class provides methods to interact with the filesystem.
 * It includes a method to list files and directories in a given path.
 */

class Filesystem
{
    protected int $debug = 0;

    public function __construct(int $debug = 0)
    {
        $this->debug = $debug;
    }

    /**
     * List files and directories in a path, optionally excluding some.
     *
     * @param string $path
     * @param array $excludes
     * @return array
     */
    public function ls(string $path, array $excludes = []): array
    {
        $files = [];
        $dirs = [];

        if (!is_dir($path)) {
            return [];
        }

        $directory = opendir($path);
        if (!$directory) {
            return [];
        }

        while (($f = readdir($directory)) !== false) {
            if ($this->debug) {
                echo "f: $f<br />\n";
            }

            if (in_array($f, ['.', '..']) || in_array($f, $excludes)) {
                continue;
            }

            $fpath = $path . DIRECTORY_SEPARATOR . $f;

            if (is_dir($fpath)) {
                $dirs[] = $f;
            } elseif (is_file($fpath)) {
                $files[] = $f;
            }
        }

        closedir($directory);

        return array_merge($dirs, $files);
    }
}
