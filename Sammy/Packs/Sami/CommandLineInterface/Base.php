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
  use php\module;
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\Base')) {
  /**
   * @trait Base
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
  trait Base {

    private $namespace;
    private $trace;

    /**
     * @var array $scriptList
     */
    private $scriptList = [];

    /**
     * @var array $config
     */
    private $config = [];

    /**
     * @var array $_commandsDirList
     */
    private $_commandsDirList = [];

    /**
     * @var array $_generatorsDirList
     */
    private $_generatorsDirList = [];

    /**
     * @var array $_templatesDirList
     */
    private $_templatesDirList = [];

    /**
     * @var array $_extensionsDirList
     */
    private $_extensionsDirList = [];

    public function __construct () {
      $this->setNameSpace (self::DEFAULT_NAMESPACE);

      $this->beforeRun (function () {
        /**
         * Map the commands directories list
         */
        foreach ($this->commandsDirList as $dir) {
          Command::SetupCommandList ($dir);

          Command::SetupCommandSetList (
            self::getDirCommandSetList ($dir)
          );
        }
      });
    }

    public function __set ($property, $value) {

      # !!!!!!! Corrigir o resultado da função abaixo
      #exit (\php\module::getModuleRootDir (__DIR__));
      # !!!!!!! fim

      /**
       * Make sure the given property is a valid
       * and non empty string.
       */
      if (is_string ($property) && $property) {

        $setterName = join ('', ['set', $property]);

        if (method_exists ($this, $setterName)) {
          $setterRef = [$this, $setterName];
          return call_user_func_array ($setterRef, [$value]);
        }

        $this->config [$property] = $value;
      }
    }

    public function __get ($property) {
      $getterName = join ('', ['get', $property]);

      if (method_exists ($this, $getterName)) {
        return call_user_func ([$this, $getterName]);
      } elseif (is_string ($property) &&
        !empty ($property) &&
        isset ($this->config [$property])) {
        return $this->config [$property];
      }
    }

    public function __call ($method, $args = []) {
      $methodRef = join ('', ['___', $method]);

      if (method_exists ($this, $methodRef)) {
        $args = array_merge ($args, [
          ['__clinterObjectContext' => $this]
        ]);

        return call_user_func_array ([$this, $methodRef], $args);
      }
    }

    public function getCommandsDir () {
      return join (DIRECTORY_SEPARATOR, [$this->src, 'commands']);
    }

    public function getTemplatesDir () {
      return join (DIRECTORY_SEPARATOR, [$this->src, 'templates']);
    }

    public function getExtensionsDir () {
      return join (DIRECTORY_SEPARATOR, [$this->src, 'extensions']);
    }

    public function getGeneratorsDir () {
      return join (DIRECTORY_SEPARATOR, [$this->src, 'generators']);
    }

    public function getDefaultCommandsDir () {
      return join (DIRECTORY_SEPARATOR, [$this->defaultSrc, 'commands']);
    }

    public function getDefaultTemplatesDir () {
      return join (DIRECTORY_SEPARATOR, [$this->defaultSrc, 'templates']);
    }

    public function getDefaultExtensionsDir () {
      return join (DIRECTORY_SEPARATOR, [$this->defaultSrc, 'extensions']);
    }

    public function getDefaultGeneratorsDir () {
      return join (DIRECTORY_SEPARATOR, [$this->defaultSrc, 'generators']);
    }

    public function getDefaultSrc () {
      return join (DIRECTORY_SEPARATOR, [self::moduleDir (), 'src']);
    }

    public function getNamespace () {
      return $this->namespace;
    }

    public function getCommandsDirList () {
      return array_merge (
        [
          $this->getDefaultCommandsDir (),
          $this->getCommandsDir ()
        ],
        $this->_commandsDirList
      );
    }

    public function getGeneratorsDirList () {
      return array_merge (
        [
          $this->getDefaultGeneratorsDir (),
          $this->getGeneratorsDir ()
        ],
        $this->_generatorsDirList
      );
    }

    public function getTemplatesDirList () {
      return array_merge (
        [
          $this->getDefaultTemplatesDir (),
          $this->getTemplatesDir ()
        ],
        $this->_templatesDirList
      );
    }

    public function getExtensionsDirList () {
      return array_merge (
        [
          $this->getDefaultExtensionsDir (),
          $this->getExtensionsDir ()
        ],
        $this->_extensionsDirList
      );
    }

    public function setNameSpace ($namespace = '') {
      if (is_string ($namespace) && $namespace) {
        $this->namespace = $namespace;
      }

      return $this;
    }

    public function setTrace ($trace = '') {
      if (self::isTrace ($trace)) {
        $this->trace = $trace;
      }
    }

    public function setCommandsDir ($commandsDir = null) {
      /**
       * Make sure the given commands dir is a non
       * empty string and it's a reference for an
       * existing directory
       */
      if (is_string ($commandsDir) &&
        is_dir ($commandsDir) &&
        !in_array ($commandsDir, $this->_commandsDirList)) {
        $this->_commandsDirList [] = realpath ($commandsDir);
      }
    }

    public function setGeneratorsDir ($generatorsDir = null) {
      /**
       * Make sure the given generators dir is a non
       * empty string and it's a reference for an
       * existing directory
       */
      if (is_string ($generatorsDir) &&
        is_dir ($generatorsDir) &&
        !in_array ($generatorsDir, $this->_generatorsDirList)) {
        $this->_generatorsDirList [] = realpath ($generatorsDir);
      }
    }

    public function setTemplatesDir ($templatesDir = null) {
      /**
       * Make sure the given templates dir is a non
       * empty string and it's a reference for an
       * existing directory
       */
      if (is_string ($templatesDir) &&
        is_dir ($templatesDir) &&
        !in_array ($templatesDir, $this->_templatesDirList)) {
        $this->_templatesDirList [] = realpath ($templatesDir);
      }
    }

    public function setExtensionsDir ($extensionsDir = null) {
      /**
       * Make sure the given extensions dir is a non
       * empty string and it's a reference for an
       * existing directory
       */
      if (is_string ($extensionsDir) &&
        is_dir ($extensionsDir) &&
        !in_array ($extensionsDir, $this->_extensionsDirList)) {
        $this->_extensionsDirList [] = realpath ($extensionsDir);
      }
    }

    public function config ($config = null) {
      if (is_array ($config) && $config) {
        /**
         * Map whole the given configurations
         */
        foreach ($config as $prop => $value) {
          $this->__set ($prop, $value);
        }
      }

      return $this;
    }

    public function registerScriptList ($scriptList = null) {
      if (is_array ($scriptList) && $scriptList) {
        $this->scriptList = array_merge ($this->scriptList, $scriptList);
      }
    }

    public function registerScript ($script, $command) {
      $this->registerScriptList ([(string)$script => $command]);
    }

    public function registerCommandDir ($dir = null) {
      $dirAbsPath = module::ReadPath ($dir);

      if (!(is_string ($dirAbsPath) && $dirAbsPath)) {
        return null;
      }

      Command::SetupCommandList ($dirAbsPath);

      return $this;
    }

    public function script ($script) {
      if (is_string ($script) && $script &&
        isset ($this->scriptList [$script])) {
        $script = $this->scriptList [$script];

        if (is_array ($script)) {
          $script = join (' ', $script);
        }

        return is_string ($script) ? $script : null;
      }
    }

    private static function moduleDir () {
      static $moduleDir = null;

      if ($moduleDir) {
        return $moduleDir;
      }

      $moduleDir = module::getModuleRootDir (__DIR__);

      return $moduleDir;
    }

    private static function isTrace ($trace) {
      return ( boolean ) (
        is_array ($trace) &&
        isset ($trace [0]) &&
        is_array ($trace [0]) &&
        isset ($trace [0]['file']) &&
        isset ($trace [0]['line'])
      );
    }
  }}
}
