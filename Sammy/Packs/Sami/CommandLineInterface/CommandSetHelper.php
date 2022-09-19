<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Sami\CommandLineInterface
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Ysare
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\Sami\CommandLineInterface {
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\CommandSetHelper')) {
  /**
   * @trait CommandSetHelper
   * Base internal trait for the
   * Sami\CommandLineInterface module.
   * -
   * This is (in the ils environment)
   * an instance of the php module,
   * wich should contain the module
   * core functionalities that should
   * be extended.
   * -
   * For extending the module, just create
   * an 'exts' directory in the module directory
   * and boot it by using the ils directory boot.
   * -
   */
  trait CommandSetHelper {
    /**
     * @method array getDirCommandSetList
     */
    protected static function getDirCommandSetList ($dir) {
      if (!(is_string ($dir) && $dir && is_dir ($dir))) {
        return [];
      }

      $dirRef = join (DIRECTORY_SEPARATOR, [$dir, '*']);

      $commandSets = [];
      $dirFileList = glob ($dirRef);

      if (!!(is_array ($dirFileList) && $dirFileList)) {
        /**
         * Map the directory file list
         * and set each one being a valid
         * command set.
         */
        foreach ($dirFileList as $file) {
          $commandSets = array_merge (
            $commandSets,
            self::getDirCommandSetList ($file)
          );
          /**
           * Make sure $file is a valid
           * command set before adding it
           * in the $commandSets array
           */
          if ($file = self::isCommandSet ($file)) {
            array_push ($commandSets, $file);
          }
        }
      }

      return $commandSets;
    }

    /**
     * @method boolean isCommandSet
     *
     * Make sure a given $dir is a command set.
     * This will verify if the given string is an
     * existsing directory and has got a '__set.php'
     * file whish should a php module exporting an array.
     *
     * @return boolean
     */
    protected static function isCommandSet ($dir) {
      if (is_string ($dir) && $dir &&
        is_dir ($dir)) {
        $setConfigFileRef = join (DIRECTORY_SEPARATOR, [$dir, '__set.php']);

        return is_file ($setConfigFileRef) ? $setConfigFileRef : false;
      }

      return false;
    }
  }}
}
