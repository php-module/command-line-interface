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
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\PluginsHelper')) {
  /**
   * @trait PluginsHelper
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
  trait PluginsHelper {
    use PluginsHelper\Base;
    use DirFileList;
    /**
     * @method Sammy\Packs\Sami\CommandLineInterface plugins
     *
     * Register a new plugin by its reference
     */
    public function plugins ($pluginRef = null) {
      $traceDatas = requires ('trace-datas')();

      $pluginModuleList = $this->getPluginModuleList (
        dirname ($traceDatas->file),
        $pluginRef
      );

      foreach ($pluginModuleList as $pluginModule) {
        $pluginDir = $pluginModule;
        /**
         * Make sure $pluginModule is a valid
         * file reference
         */
        if (is_file ($pluginModule)) {
          $pluginDir = dirname ($pluginModule);
        }

        $this->asign4plguin ($pluginDir);
      }

      return $this;
    }

    private function asign4plguin ($pluginDir) {
      $propertyList = [
        'commands',
        'templates',
        'generators',
        'extensions'
      ];

      foreach ($propertyList as $folderName) {
        $setterRef = join ('', ['set', $folderName . 'Dir']);
        $folderRef = join (DIRECTORY_SEPARATOR, [
          $pluginDir, $folderName
        ]);

        if (!is_dir ($folderRef)) {
          $folderRef = join (DIRECTORY_SEPARATOR, [
            $pluginDir, 'src', $folderName
          ]);
        }

        if (method_exists ($this, $setterRef) &&
          is_dir ($folderRef)) {
          call_user_func ([$this, $setterRef], $folderRef);
        }
      }

      return $this;
    }
  }}
}
