<?php

use Sammy\Packs\Sami\CommandLineInterface\Console;

$module->exports = [
  'name' => 'sometest',
  'handler' => function () {
    Console::Success ('This is a test..!!');
  }
];
