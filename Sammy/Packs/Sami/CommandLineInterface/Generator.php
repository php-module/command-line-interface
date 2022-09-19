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
  if (!class_exists ('Sammy\Packs\Sami\CommandLineInterface\Generator')) {
  /**
   * @class Generator
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
  class Generator {
    use Generator\Base;

    /**
     * @method void Generate
     */
    public static function Handle () {
      /**
       * Get whole the command parameters
       */
      list ($parameters) = Arguments::GetAll ();

      $generator = $parameters->first;

      #$props = array_merge ($options->all (), [
      #  'name' => $parameters->second
      #]);

      #$props = addslashes (json_encode ($props));

      #system ('php samils template:draw '.$generator.'.template.css --props="' . $props . '" > templateOutPut.php');

      self::Generate ($generator);
    }

    /**
     * @method void HandleList
     */
    public static function HandleList ($generatorList = []) {
      if (!!(is_array ($generatorList) && $generatorList)) {
        list ($parameters, $options) = Arguments::GetAll ();

        foreach ($generatorList as $generator) {
          /**
           * Make sure $generator is a valid
           * string and it is not empty.
           */
          if (!!(is_string ($generator) && trim ($generator))) {
            # verify if it has to skip the current
            # template generation
            $sikpperName = join ('-', ['skip', $generator]);

            if (!$options->$sikpperName) {
              self::Generate ($generator);
            }
          }
        }
      }
    }

    /**
     * @method void Generate
     */
    public static function Generate ($generator) {

      $generatorDatas = self::Read ($generator);

      $templates = [];

      list ($parameters, $options) = Arguments::GetAll ();

      #exit ('UUAS -> ' . $generator);

      if (isset ($generatorDatas ['templates']) &&
        is_array ($generatorDatas ['templates'])) {
        $templates = $generatorDatas ['templates'];
      }

      foreach ($templates as $key => $template) {
        $templateDatas = [];
        $name = $parameters->second;

        $templateKey = $key;

        if (!(is_string ($templateKey) &&
            !empty ($templateKey))) {
          $templateKey = $template;
        }

        if (is_string ($templateKey) &&
          !empty ($templateKey) &&
          isset ($generatorDatas [$templateKey]) &&
          is_array ($generatorDatas [$templateKey])) {
          $templateDatas = $generatorDatas [$templateKey];
        }

        $props = array_merge ($options->all (), [
          'name' => $name
        ]);

        # Verify if there is a rename funciton
        if (isset ($templateDatas ['props']) &&
          is_callable ($templateDatas ['props'])) {
          $defaultProps = call_user_func ($templateDatas ['props'], $props);

          $props = array_merge ($props, $defaultProps);
        }

        # Verify if there is a rename funciton
        if (isset ($templateDatas ['rename']) &&
          is_callable ($templateDatas ['rename'])) {
          $props ['name'] = call_user_func ($templateDatas ['rename'], $name);
        }

        # verify if it has to skip the current
        # template generation
        $sikpperName = join ('-', ['skip', $templateKey]);

        if ($options->$sikpperName) {
          continue;
        }

        $templateDatas = array_merge ($templateDatas, [
          'props' => $props
        ]);

        #print_r($templateDatas);
        #exit (0);

        Template::Generate ($template, $templateDatas);

        #$props = addslashes (json_encode ($props));

        #exit ($props);

        #system ('php samils template:draw '.$template.' --props="' . $props . '" > templateOutPut-'.$template.'.php');

        #echo 'Template => ', $template, "\n";
        #print_r($templateDatas);
        #echo "\n\n\n";
      }

      if (isset ($generatorDatas ['include']) &&
        is_array ($generatorDatas ['include']) &&
        count ($generatorDatas ['include'])) {
        $includes = $generatorDatas ['include'];

        self::HandleList ($includes);
      }
    }
  }}
}
