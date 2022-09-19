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
  if (!class_exists ('Sammy\Packs\Sami\CommandLineInterface\Options')) {
  /**
   * @class Options
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
  class Options {
    use Options\Base;
    use Arguments\Base;

    /**
     * @method array only
     *
     * Get a list of options values and return
     * them in anoter list as key-value pairs.
     *
     * @return array
     */
    private function ___get () {
      $requiredOptions = func_get_args ();
      $requiredOptionsCount = count ($requiredOptions);

      $requiredOptionsList = [];

      for ($i = 0; $i < $requiredOptionsCount; $i++) {
        $requiredOption = $requiredOptions [ $i ];

        if (is_array ($requiredOption)) {
          $requiredOptionArray = call_user_func_array ([$this, '___get'], $requiredOption);

          if (!is_array ($requiredOptionArray)) {
            $requiredOptionArray = [];
          }

          $requiredOptionsList = array_merge (
            $requiredOptionsList,
            $requiredOptionArray
          );
        } elseif (isset($this->dataObjectBase [ $requiredOption ])) {
          array_push ($requiredOptionsList,
            $this->dataObjectBase[ $requiredOption ]
          );
        } else {
          array_push ($requiredOptionsList, null);
        }
      }

      return $requiredOptionsList;
    }

    /**
     * @method array only
     *
     * Get a list of options values and return
     * them in anoter list as key-value pairs.
     *
     * @return array
     */
    private function ___only () {
      $requiredOptions = func_get_args ();
      $requiredOptionsCount = count ($requiredOptions);

      $requiredOptionsList = [];

      for ($i = 0; $i < $requiredOptionsCount; $i++) {
        $requiredOption = $requiredOptions [ $i ];

        if (is_array ($requiredOption)) {
          $requiredOptionArray = call_user_func_array ([$this, '___only'], $requiredOption);

          if (!is_array ($requiredOptionArray)) {
            $requiredOptionArray = [];
          }

          $requiredOptionsList = array_merge (
            $requiredOptionsList,
            $requiredOptionArray
          );
        } elseif (isset($this->dataObjectBase [ $requiredOption ])) {
          $requiredOptionsList [$requiredOption] = $this->dataObjectBase[ $requiredOption ];
        } else {
          $requiredOptionsList [$requiredOption] = null;
        }
      }

      return $requiredOptionsList;
    }

    private function ___all () {
      return $this->dataObjectBase;
    }

    private function get ($option = null) {
      if (!(is_string ($option) && $option)) {
        return;
      }

      $datas = $this->___only ($option);

      return $datas [$option];
    }
  }}
}
