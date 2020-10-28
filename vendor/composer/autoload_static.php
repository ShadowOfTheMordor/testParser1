<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb110d5434c5a28c65b7a17cd93e1ef84
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Faker\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Faker\\' => 
        array (
            0 => __DIR__ . '/..' . '/fzaninotto/faker/src/Faker',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb110d5434c5a28c65b7a17cd93e1ef84::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb110d5434c5a28c65b7a17cd93e1ef84::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}