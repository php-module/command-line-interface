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
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\Command\CoreProps')) {
  /**
   * @trait CoreProps
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
  trait CoreProps {
    /**
     * @var string $name
     *
     * Command name; the reference
     * to use when running it.
     */
    private $name;

    /**
     * @var array $options
     *
     * Command options;
     * A list of the current command
     * properties.
     */
    private $options = [];

    /**
     * @method void __set
     */
    public function __set ($property, $value = null) {
      if (!(is_string ($property))) {
        return;
      }

      $propertySetterName = join ('', ['set', ucfirst ($property)]);

      if (method_exists ($this, $propertySetterName)) {
        $propertySetterRef = [$this, $propertySetterName];
        return call_user_func_array ($propertySetterRef, $value);
      }

      $this->options [strtolower ($property)] = $value;
    }

    /**
     * @method mixed __get
     */
    public function __get ($property) {
      $properties = $this->getAllProps ();

      if (is_string ($property) &&
        !empty ($property) &&
        isset ($properties [strtolower ($property)])) {
        return $properties [strtolower ($property)];
      }
    }

    /**
     * @method string getName
     */
    public function getName () {
      return $this->name;
    }

    /**
     * @method Sammy\Packs\Sami\CommandLineInterface\Command asign
     *
     * Asign the current Command object with the
     * given $propertyList.
     *
     * Set each property in the given array as a new
     * for the current object, whish value should be the
     * same as the property value in the property list.
     *
     * @param array $propertyList
     *
     * The property list to asign the current
     * object.
     */
    public function asign ($propertyList = []) {
      if (!!(is_array ($propertyList) && $propertyList)) {
        foreach ($propertyList as $property => $value) {
          $this->__set ($property, $value);
        }
      }
    }

    /**
     * @method void addAlias
     *
     * Add a new alias for the current command name.
     *
     * @param string $alias
     *
     * The alias for the command name.
     */
    public function addAlias ($alias = null) {
      self::registerAlias ($alias, $this->name);
    }

    /**
     * @method getAllProps
     */
    protected function getAllProps () {
      return array_merge (['name' => $this->name], $this->options);
    }
  }}
}
