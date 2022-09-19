<?php

use php\module;

# include php-module
include_once '\vendor\php_modules\core\autoload.php';

include_once '\Users\hp\Documents\Sammy-Packages\Samils\vendor\samils\callable-object\autoload.php';
include_once '\Users\hp\Documents\Sammy-Packages\Samils\vendor\samils/samils-component-functions\autoload.php';

requires ('fs');

$clinter = requires ('command-line-interface');

$cli = $clinter ();

$root = module::getModuleRootDir (__DIR__);

$cli->config (['src' => join ('/', [$root, 'src'])])
  ->execute ();

exit ('IKAS');
