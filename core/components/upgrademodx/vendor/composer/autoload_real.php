<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInite1ddb72e109c6e1e613a19079efb21dc
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInite1ddb72e109c6e1e613a19079efb21dc', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInite1ddb72e109c6e1e613a19079efb21dc', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInite1ddb72e109c6e1e613a19079efb21dc::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
