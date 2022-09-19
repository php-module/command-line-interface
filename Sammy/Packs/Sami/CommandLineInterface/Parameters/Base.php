<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Sami\CommandLineInterface\Parameters
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
namespace Sammy\Packs\Sami\CommandLineInterface\Parameters {
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\Parameters\Base')) {
  /**
   * @trait Base
   * Base internal trait for the
   * Sami\CommandLineInterface\Parameters module.
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
  trait Base {
    private function ___first () {
      return $this->eq (0);
    }

    private function ___second () {
      return $this->eq (1);
    }

    private function ___third () {
      return $this->eq (2);
    }

    private function ___last () {
      return $this->eq (-1);
    }

    private function ___eq ($index = null) {
      if (is_int ($index)) {
        $index = $index >= 0 ? $index : $index + count ($this->dataObjectBase);

        if (isset ($this->dataObjectBase [$index])) {
          return $this->dataObjectBase [$index];
        }
      }
    }

    private function ___match ($regularExpression = '/.*/') {
      $regularExpression = !is_string($regularExpression) ? '/.*/' : (
        $regularExpression
      );

      $dataObjectBase = $this->getDataObjectBase ();
      $dataObjectBaseCount = count ($dataObjectBase);
      $optionsmatchingRegularExpression = [];

      for ($i = 0; $i < $dataObjectBaseCount; $i++) {
        if (@preg_match ($regularExpression, $dataObjectBase[$i])) {
          array_push ($optionsmatchingRegularExpression,
            $dataObjectBase [ $i ]
          );
        }
      }

      return $optionsmatchingRegularExpression;
    }

    private function ___count () {
      return count ($this->dataObjectBase);
    }

    private function ___filter (Closure $filter) {
      $filteredParameters = array ();

      for ($i = 0; $i < count ($this->dataObjectBase); $i++) {
        if ($filter (strtolower ($this->dataObjectBase [$i]))) {
          array_push ($filteredParameters, $this->dataObjectBase [$i]);
        }
      }

      return $filteredParameters;
    }

    private function ___whole () {
      return $this->dataObjectBase;
    }

    private function ___all () {
      return $this->whole ();
    }

    private function ___slice (int $offset, int $limit = 1) {
      $dataObjectBaseCount = $this->count ();

      if ( $offset < $dataObjectBaseCount ) {
        $arguments = func_get_args ();
        return call_user_func_array ('array_slice',
          array_merge ([$this->dataObjectBase], $arguments)
        );
      }
    }

    private function getFirst () {
      return $this->___first ();
    }

    private function getSecond () {
      return $this->___second ();
    }

    private function getThird () {
      return $this->___third ();
    }

    private function getAll () {
      return $this->___all ();
    }

    private function getLast () {
      return $this->___last ();
    }
  }}
}
