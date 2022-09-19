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
  use Sammy\Packs\Sami\CommandLineInterface;
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!class_exists ('Sammy\Packs\Sami\CommandLineInterface\Context')) {
  /**
   * @class Context
   * Base internal class for the
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
  class Context {

    public static function GetContext () {
      $backTrace = debug_backtrace ();

      $key = '__clinterObjectContext';

      foreach ($backTrace as $traceDatas) {
        /**
         * Make sure there is a
         */
        if (isset ($traceDatas ['args']) &&
          count ($args = $traceDatas ['args']) >= 1 &&
          is_array ($lastArg = $args [-1 + count ($args)]) &&
          count ($lastArg) >= 1 &&
          isset ($lastArg [0]) &&
          is_array ($lastArg [0]) &&
          isset ($lastArg [0][$key]) &&
          is_object ($context = $lastArg [0][$key]) &&
          $context instanceof CommandLineInterface) {
          return $context;
        }
      }
    }

    public static function GetInputFile () {
      $backTrace = debug_backtrace ();

      $lastTrace = $backTrace [-1 + count ($backTrace)];

      if (isset ($lastTrace ['file'])) {
        return $lastTrace ['file'];
      }
    }

    public static function GetInputCommand () {
      $inputFile = self::GetInputFile ();

      $args = self::filterIncludedFiles ();

      return join (' ', ['php', $args]);
    }

    private static function filterIncludedFiles () {
      $args = $_SERVER ['argv'];

      $includedFiles = get_included_files ();
      #print_r(array_slice (get_included_files (), 0, 15));
      $argsLen = count ($args);

      if (count ($args) <= 0) {
        return '';
      }

      while ($argsLen-- >= 0) {

        if ($argsLen < 0) {
          break;
        }

        $filePathAlternates = [
          $args [$argsLen],
          join (DIRECTORY_SEPARATOR, [
            realpath (null),
            $args [$argsLen]
          ])
        ];

        foreach ($filePathAlternates as $filePath) {
          if (in_array ($filePath, $includedFiles)) {
            $fileList = array_slice ($args, 0, $argsLen + 1);

            return join (' ', $fileList);
          }
        }
      }

      return null;
    }
  }}
}
