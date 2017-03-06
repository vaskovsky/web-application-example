<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5a2d449fc96bc7a84f2e94784634401f
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'AVaskovsky\\WebApplication\\' => 26,
            'AVaskovsky\\WebApplicationExample\\' => 33,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'AVaskovsky\\WebApplication\\' => 
        array (
            0 => __DIR__ . '/..' . '/vaskovsky/web-application/src',
        ),
        'AVaskovsky\\WebApplicationExample\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5a2d449fc96bc7a84f2e94784634401f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5a2d449fc96bc7a84f2e94784634401f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}