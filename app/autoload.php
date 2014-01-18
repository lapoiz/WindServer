<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;


/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

if (!function_exists('intl_get_error_code')) {
    require __DIR__.'/../vendor/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->registerPrefixFallbacks(
        array(__DIR__.'/../vendor/Symfony/Component/Locale/Resources/stubs')
    );
}

return $loader;
