<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Sami\CommandLineInterface\Command
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
namespace Sammy\Packs\Sami\CommandLineInterface\Command {
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\Command\CommandSetHelper')) {
  /**
   * @trait CommandSetHelper
   * Base internal trait for the
   * Sami\CommandLineInterface\Command module.
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
     * @method void SetupCommandSetList
     */
    public static function SetupCommandSetList ($setList = []) {
      if (!(is_array ($setList) && $setList)) {
        return;
      }

      $a = [];

      foreach ($setList as $i => $set) {
        if (!!is_file ($set)) {
          $setConfig = requires ($set);

          self::AsignCommandSet ($setConfig, $set);
        }
      }
    }

    /**
     * @method void AsignCommandSet
     */
    protected static function AsignCommandSet ($setConfig, $set) {
      $setConfigKeys = array_keys ($setConfig);

      foreach ($setConfigKeys as $key) {
        $asignerName = join ('', ['AsignCommandSet', $key]);

        if (method_exists (self::class, $asignerName)) {
          forward_static_call_array ([self::class, $asignerName], func_get_args ());
        }
      }
    }

    /**
     * @method boolean isCommandSet
     */
    public static function isCommandSet ($commandSet = null) {
      if (!(is_string ($commandSet) && $commandSet)) {
        return false;
      }

      #print_r (self::$aliasList);

      $commandSetSlices = preg_split ('/:/', $commandSet);

      $commandSetPrefix = $commandSetSlices [0];

      $commandSetSufix = array_slice (
        $commandSetSlices, 1,
        count ($commandSetSlices)
      );

      if (isset (self::$aliasList [$commandSetPrefix]) &&
        is_string (self::$aliasList [$commandSetPrefix]) &&
        !empty (self::$aliasList [$commandSetPrefix]) &&
        is_dir (self::$aliasList [$commandSetPrefix])) {
        $commandSetPath = self::$aliasList [$commandSetPrefix];

        $commandSetRef = [self::GetCommandNameFromPath ($commandSetPath)];

        $commandRef = array_merge ($commandSetRef, $commandSetSufix);

        return join (':', $commandRef);
      }
    }
  }}
}
