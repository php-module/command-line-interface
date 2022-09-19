<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Sami\CommandLineInterface\Template
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
namespace Sammy\Packs\Sami\CommandLineInterface\Template {
  use Sammy\Packs\Sami\CommandLineInterface\Context;
  use Sammy\Packs\Sami\CommandLineInterface\Console;
  use Sammy\Packs\FileSystem\Folder;

  use php\module;
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\Template\Base')) {
  /**
   * @trait Base
   * Base internal trait for the
   * Sami\CommandLineInterface\Template module.
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

    private static function stripDot ($extension) {
      $extension = preg_replace ('/^\.+/', '', $extension);
      $extension = preg_replace ('/\.+$/', '', $extension);

      return trim ($extension);
    }
    /**
     * @method void Generate
     */
    public static function Generate ($template, $options = []) {

      if (!is_array ($options)) {
        $options = [];
      }

      $props = [];

      if (isset ($options ['props']) &&
        is_array ($options ['props'])) {
        $props = $options ['props'];
      }

      $fileExtension = pathinfo ($template, 4);

      if (!$fileExtension) {
        $fileExtension = 'php';
      }

      if (isset ($options ['extension']) &&
        is_callable ($e = $options ['extension'])) {
        $fileExtension = self::stripDot (call_user_func (
          $e, $template
        ));
      } elseif (isset ($options ['extension']) &&
        is_string ($e = $options ['extension'])) {
        $fileExtension = self::stripDot ($e);
      } elseif (!$fileExtension) {
        $fileExtension = 'php';
      }

      $fileName = $props ['name'];

      if (!!$fileExtension) {
        $fileName = join ('.', [
          $props ['name'],
          $fileExtension
        ]);
      }

      $target = self::stripLastSlash ($options ['target']);
      $target = join (DIRECTORY_SEPARATOR, [$target, $fileName]);

      $props ['__target'] = $target;
      $props = base64_encode (json_encode ($props));
      $root = module::getModuleRootDir (__DIR__);
      #$appFile = realpath ("{$root}/src/app.php");

      #exit ($appFile);

      $clinterInputCommand = Context::GetInputCommand ();

      Folder::Create (dirname ($target));

      @system ("{$clinterInputCommand} template:draw ".$template.' --props="' . $props . '" > ' . $target);

      Console::Success ('Created', $target, "\n");

      #echo 'Template => ', $template, "\n";
      #print_r($templateDatas);
      #echo "\n\n\n";
    }

    public static function GetTemplatePath ($template) {
      $cli = Context::GetContext ();
      $templateDirAlternates = $cli->templatesDirList;
      $templateFileExtension = pathinfo ($template, 4);
      #$template = preg_replace ('/(\.template\.(.+))$/i', '', $template);

      $template = pathinfo ($template, 8);

      $templateFileName = $template;

      if (!!$templateFileExtension) {
        $templateFileName = join ('.', [
          $template, $templateFileExtension
        ]);
        #$templateFileExtension = 'template.php';
      }

      foreach ($templateDirAlternates as $dir) {
        $templatePath = join (DIRECTORY_SEPARATOR,
          [$dir, $templateFileName]
        );

        if ($path = self::isTemplateFile ($templatePath)) {
          return realpath ($path);
        }
      }
    }

    private static function isTemplateFile ($filePath) {
      $filePathSufixes = [
        '.template.php',
        '.php',
        '.template',
        ''
      ];

      foreach ($filePathSufixes as $sufix) {
        $path = join ('', [$filePath, $sufix]);

        if (is_file ($path)) {
          return $path;
        }
      }

      return false;
    }

    private static function stripLastSlash ($string = '') {
      return preg_replace ('/(\\\|\/)+$/', '', $string);
    }
  }}
}
