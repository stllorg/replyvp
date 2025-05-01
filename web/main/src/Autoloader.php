<?php

class Autoloader {
    public static function register() {
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            $file = __DIR__ . '/../' . $file;
            
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
} 