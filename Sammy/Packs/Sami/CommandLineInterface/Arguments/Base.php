<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Sami\CommandLineInterface\Arguments
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
namespace Sammy\Packs\Sami\CommandLineInterface\Arguments {
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\Arguments\Base')) {
  /**
   * @trait Base
   * Base internal trait for the
   * Sami\CommandLineInterface\Arguments module.
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
    /**
     * @var array Data Object Base
     * An array containg the arguments list with
     * the key-value pairs pattern.
     * Used when a specific command is executed
     * to get informations from the same's syntax.
     */
    protected $dataObjectBase = [];

    public function __construct ($initialDatas = null) {
      $this->setDataObjectBase ($initialDatas);
    }

    /**
     * @method array setDataObjectBase
     * set the object initial datas
     */
    protected function setDataObjectBase ($initialDatas) {
      if (is_array ($initialDatas)) {
        return $this->dataObjectBase = array_merge (
          $this->dataObjectBase, $initialDatas
        );
      }
    }

    /**
     * @method array replaceDataObjectBase
     * replace the object datas
     */
    public function replaceDataObjectBase ( $newDatas ) {
      if (is_array ($initialDatas)) {
        return $this->dataObjectBase = $newDatas;
      }
    }

    public function getDataObjectBase () {
      return preg_split ('/\s+/',
        strtolower (join (' ', $this->dataObjectBase))
      );
    }

    private function keys () {
      return array_keys ($this->getDataObjectBase ());
    }

    /**
     * @method boolean contains
     * verify if an argument name is
     * contained in the current argument
     * list
     */
    private function contains ($argumentPropertyName) {
      $dataObjectBase = $this->getDataObjectBase ();

      return in_array (
        strtolower ($argumentPropertyName),
        array_keys ($dataObjectBase)
      );
    }

    protected static function getClassObject () {
      $backTrace = debug_backtrace ();

      if (isset ($backTrace [2]) &&
        is_array ($backTrace [2]) &&
        isset ($backTrace [2]['args']) &&
        is_array ($backTrace [2]['args'])) {
        $args = $backTrace [2]['args'];

        foreach ($args as $arg) {
          if (is_object ($arg) &&
            get_class ($arg) === static::class) {
            return $arg;
          }
        }
      }
    }

    public static function __callStatic ($method, $arguments) {
      $object = self::getClassObject ();
      $methodName = join ('', ['___', $method]);

      if (is_object ($object) &&
        method_exists ($object, $methodName)) {
        return call_user_func_array ([$object, $methodName], $arguments);
      }
    }

    public function __call ($method, $arguments) {
      $methodName = join ('', ['___', $method]);

      if (method_exists ($this, $methodName)) {
        return call_user_func_array ([$this, $methodName], $arguments);
      }
    }

    public function __get ($property = null) {
      $getterName = join ('', ['get', $property]);

      if (method_exists ($this, $getterName)) {
        return call_user_func ([$this, $getterName]);
      } elseif (method_exists ($this, 'get')) {
        return call_user_func_array ([$this, 'get'], [$property]);
      }
    }
  }}
}
