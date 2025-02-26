<?php

namespace App\Helpers;

use Symfony\Component\Yaml\Yaml;

class YamlConfig
{
    private static $config;

    public static function load()
    {
        if (!self::$config) {
            $filePath = base_path('/config/wmo_config.yml');
            if (!file_exists($filePath)) {
                throw new \Exception("Configuration file not found: {$filePath}");
            }
            self::$config = Yaml::parseFile($filePath);
        }
        return self::$config;
    }

    public static function get($key, $default = null)
    {
        $config = self::load();
        return array_reduce(explode('.', $key), function ($carry, $part) {
            return $carry[$part] ?? null;
        }, $config) ?? $default;
    }
}