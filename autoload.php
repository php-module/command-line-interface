<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\ProjectMap
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\ProjectMap {
  $autoloadFile = __DIR__ . '/vendor/autoload.php';

  if (is_file ($autoloadFile)) {
    include_once $autoloadFile;
  }

  $initFileListDirPath = join (DIRECTORY_SEPARATOR, [
    __DIR__, 'src', 'Sammy', 'Packs', 'Sami', 'Cli'
  ]);

  $initFileListRe = join (DIRECTORY_SEPARATOR, [
    $initFileListDirPath, '*.php'
  ]);

  $initFileList = glob ($initFileListRe);

  if (count ($initFileList) >= 1) {
    foreach ($initFileList as $filePath) {
      include_once $filePath;
    }
  }
}
