<?php

namespace ThreeTagger\MyFramework\Utils;

class Path
{
    public static function GetFromSourcePath($path): string
    {
        return __DIR__ . '/../' . $path;
    }

    public static function GetFromBasePath($path): string
    {
        return __DIR__ . '/../../' . $path;
    }
}