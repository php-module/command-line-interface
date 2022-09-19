<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Sami\CommandLineInterface\Generator
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
namespace Sammy\Packs\Sami\CommandLineInterface\Generator {
  use Sammy\Packs\Sami\CommandLineInterface\Context;
  use Sammy\Packs\Sami\CommandLineInterface\Console;
  use Sammy\Packs\Sami\CommandLineInterface\DirFileList;
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\Generator\Base')) {
  /**
   * @trait Base
   * Base internal trait for the
   * Sami\CommandLineInterface\Generator module.
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
    use DirFileList;

    /**
     * @var array $aliases
     */
    private static $aliases = [];

    /**
     * @method array Read
     */
    public static function Read ($generator) {

      $generatorPath = self::GetGeneratorPath ($generator);

      self::SetUpGeneratorAliases ();

      if (!empty ($generatorPath)) {
        $generatorPath = realpath ($generatorPath);
      }

      $generatorDatas = requires ($generatorPath);

      if (!(self::validGeneratorDatas ($generatorDatas) ||
        $generatorDatas = self::alias ($generator))) {
        Console::Error ('Unkown generator', $generator);
        exit (0);
      }

      $compileConfig = requires ('gogue-plugin-config-compiler');

      return $compileConfig ($generatorDatas);
    }

    private static function validGeneratorDatas ($datas) {
      if (!(is_array ($datas) &&
        isset ($datas ['templates']) &&
        is_array ($datas ['templates']))) {
        return (boolean)(
          is_array ($datas) &&
          isset ($datas ['includes']) &&
          is_array ($datas ['includes']) &&
          count ($datas ['includes']) >= 1
        );
      }

      return true;
    }

    private static function SetUpGeneratorAliases () {
      $cli = Context::GetContext ();

      $generatorsDirFileList = array_merge (
        self::getDirFileList ($cli->defaultGeneratorsDir),
        self::getDirFileList ($cli->generatorsDir)
      );

      foreach ($generatorsDirFileList as $generatorFile) {
        $generator = requires ($generatorFile);

        if (self::validGeneratorDatas ($generator) &&
          isset ($generator ['aliases']) &&
          is_array ($generator ['aliases'])) {
          self::setAlias ($generator ['aliases'], $generator);
        }
      }
    }

    private static function alias ($alias) {
      if (is_string ($alias) && $alias &&
        isset (self::$aliases [$alias]) &&
        self::validGeneratorDatas (self::$aliases [$alias])) {
        return self::$aliases [$alias];
      }

      return false;
    }

    private static function setAlias ($aliasList, $generator) {
      if (is_array ($aliasList) && $aliasList) {
        /**
         * Map whole the aliases inside the given
         * array ...
         */
        foreach ($aliasList as $alias) {
          if (is_string ($alias) && $alias &&
            !isset (self::$aliases [strtolower ($alias)])) {
            self::$aliases [strtolower ($alias)] = $generator;
          }
        }
      }
    }

    public static function GetGeneratorPath ($generator) {
      $cli = Context::GetContext ();
      $generatorDirAlternates = $cli->generatorsDirList;

      foreach ($generatorDirAlternates as $dir) {
        $generatorPath = join ('/', [
          $dir, $generator . '.generator.php'
        ]);

        if (is_file ($generatorPath)) {
          return $generatorPath;
        }
      }
    }
  }}
}
