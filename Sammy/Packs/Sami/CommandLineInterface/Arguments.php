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
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!class_exists ('Sammy\Packs\Sami\CommandLineInterface\Arguments')) {
  /**
   * @class Arguments
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
  class Arguments {
    /**
     * @method mixed GetAll
     */
    public static function GetAll () {
      $backTrace = debug_backtrace ();

      if (!!(is_array ($backTrace) && $backTrace)) {
        /**
         * Assuming that the current stack trace
         * is not empty, try finding a function call
         * receiving two arguments; whish should be
         * two Parameters and Options objects.
         *
         * Considere them as the function arguments and
         * return them inside an array as they was sent for
         * the found function call in the stack.
         */
        foreach ($backTrace as $i => $trace) {
          /**
           * Verify if the current trace array
           * contains a 'args' property as an array
           * of two or more positions and the first
           * as the second one is an object.
           *
           * Make sure that the object type is the
           * wanted one in order using them as the
           * current function arguments.
           */
          if (isset ($trace ['args']) &&
            is_array ($trace ['args']) &&
            count ($trace ['args']) >= 2 &&
            $trace ['args'][0] instanceof Parameters &&
            $trace ['args'][1] instanceof Options) {
            return [$trace ['args'][0], $trace ['args'][1]];
          }
        }
      }
    }

    /**
     * @method mixed GetAll
     */
    public static function GetParameters () {
      $backTrace = debug_backtrace ();

      if (!!(is_array ($backTrace) && $backTrace)) {
        /**
         * Assuming that the current stack trace
         * is not empty, try finding a function call
         * receiving two arguments; whish should be
         * two Parameters and Options objects.
         *
         * Considere them as the function arguments and
         * return them inside an array as they was sent for
         * the found function call in the stack.
         */
        foreach ($backTrace as $i => $trace) {
          /**
           * Verify if the current trace array
           * contains a 'args' property as an array
           * of two or more positions and the first
           * as the second one is an object.
           *
           * Make sure that the object type is the
           * wanted one in order using them as the
           * current function arguments.
           */
          if (isset ($trace ['args']) &&
            is_array ($trace ['args']) &&
            count ($trace ['args']) >= 2 &&
            $trace ['args'][0] instanceof Parameters) {
            return $trace ['args'][0];
          }
        }
      }
    }

    /**
     * @method mixed GetAll
     */
    public static function GetOptions () {
      $backTrace = debug_backtrace ();

      if (!!(is_array ($backTrace) && $backTrace)) {
        /**
         * Assuming that the current stack trace
         * is not empty, try finding a function call
         * receiving two arguments; whish should be
         * two Parameters and Options objects.
         *
         * Considere them as the function arguments and
         * return them inside an array as they was sent for
         * the found function call in the stack.
         */
        foreach ($backTrace as $i => $trace) {
          /**
           * Verify if the current trace array
           * contains a 'args' property as an array
           * of two or more positions and the first
           * as the second one is an object.
           *
           * Make sure that the object type is the
           * wanted one in order using them as the
           * current function arguments.
           */
          if (isset ($trace ['args']) &&
            is_array ($trace ['args']) &&
            count ($trace ['args']) >= 2 &&
            $trace ['args'][1] instanceof Options) {
            return $trace ['args'][1];
          }
        }
      }
    }
  }}
}
