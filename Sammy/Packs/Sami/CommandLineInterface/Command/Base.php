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
  use Sammy\Packs\Sami\CommandLineInterface\DirFileList;
  use Sammy\Packs\Sami\CommandLineInterface\Context;
  use Sammy\Packs\Sami\CommandLineInterface\Command;
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\Command\Base')) {
  /**
   * @trait Base
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
  trait Base {
    use DirFileList;
    use CommandSetHelper;
    use CommandSetAsigners;
    /**
     * @var array $commandList
     *
     * A list of whole the commands
     * for the command line interface.
     */
    private static $commandList = [];

    /**
     * @var array $commandDirectoryList
     */
    private static $commandDirectoryList = [];

    /**
     * @var array $aliasList
     *
     * A list of whole the aliases
     * for the command line interface.
     */
    private static $aliasList = [];

    /**
     * @method void Register
     *
     * Register a new command line interface
     * command name with its configurations
     * datas.
     *
     * @param string $name
     *
     * Command name.
     *
     * @param array $options
     *
     * Command options.
     */
    public static function Register ($name, $options = []) {
      if (!(is_string ($name) && $name)) {
        return;
      }

      $name = strtolower ($name);

      if (!(isset (self::$commandList [$name]) &&
          self::$commandList [$name] instanceof Command)) {

        $command = new static ($name, $options);

        $commandTarget = $command->handler ? $command->handler : $name;

        self::registerAlias ($command->aliases, $commandTarget);

        self::$commandList [$name] = $command;
      }
    }

    /**
     * @method void SetupCommandList
     *
     * Setup command list based in a directory path;
     * Map the directory and its subdirectory files
     * in order getting whole the php modules inside it
     * and configure  eac command for it according to the
     * given configuration datas.
     *
     * @param string $commandsDir
     *
     * Cli commands directory.
     */
    public static function SetupCommandList ($commandsDir = null) {

      if (!is_dir ($commandsDir)) {
        return false;
      }

      if (!in_array ($commandsDir, self::$commandDirectoryList)) {
        self::$commandDirectoryList [] = $commandsDir;
      }

      $done = true;

      foreach (self::getDirFileList ($commandsDir) as $file) {
        $command = requires ($file);

        if (self::IsCommandArray ($command)) {
          $commandName = self::GetCommandNameFromPath ($file);

          self::Register ($commandName, $command);
        }
      }
    }

    public static function IsCommandArray ($commandArray) {
      $commandName = 'handler';

      if (is_array ($commandArray) &&
        isset ($commandArray ['name']) &&
        is_string ($commandArray ['name'])) {
        $commandName = $commandArray ['name'];
      }

      if (is_array ($commandArray) &&
        ((isset ($commandArray ['handler']) &&
        is_callable ($handler = $commandArray ['handler'])) ||
        (isset ($commandArray [$commandName]) &&
        is_callable ($handler = $commandArray [$commandName])))) {
        return $handler;
      }
    }

    /**
     * @method void registerAlias
     *
     * Register a new command line interface
     * alias for a command name.
     *
     * @param string $alias
     *
     * Command alias.
     *
     * @param string $command
     *
     * Alias reference command.
     * The command name this alias points to.
     */
    private static function registerAlias ($alias, $command) {
      if (is_string ($alias) && $alias) {
        $alias = strtolower ($alias);

        if (!(isset (self::$aliasList [$alias]) &&
            is_string (self::$aliasList [$alias]))) {
          self::$aliasList [$alias] = $command;
        }
      } elseif (is_array ($alias)) {
        foreach ($alias as $a) {
          self::registerAlias ($a, $command);
        }
      }
    }

    /**
     * @method boolean|Sammy\Packs\Sami\CommandLineInterface\Command Exists
     */
    public static function Exists ($name = '') {
      if (is_string ($name) && $name &&
        isset (self::$commandList [strtolower ($name)])) {
        $cmd = self::$commandList [strtolower ($name)];

        return $cmd instanceof Command ? $cmd : false;
      }

      return false;
    }

    /**
     * @method array All
     */
    public static function All () {
      return self::$commandList;
    }

    /**
     * @method array GetAliases
     */
    public static function GetAliases () {
      return self::$aliasList;
    }

    /**
     * @method array Alias
     */
    public static function Alias ($alias = null) {
      # Make sure the $alias variable is a valid
      # and non empty string in order using it as
      # a key for the $aliasList array property;
      # and so, verify if it is an existing property
      # inside of it.
      if (is_string ($alias) && $alias &&
        # Verify if it is an existing property
        # inside the $aliasList array class properrty
        isset (self::$aliasList [$alias]) &&
        # Now, verify if the existing property is a
        # valid string as a valid command indide the
        # $commandList class propertry
        # (by using the self::Exists method)
        is_string (self::$aliasList [$alias]) &&
        !empty (self::$aliasList [$alias]) &&
        self::Exists (self::$aliasList [$alias])) {
        return self::$aliasList [$alias];
        # Make sure the $alias variable is a valid
        # and non empty string in order using it as
        # a key for the $aliasList array property;
        # and so, verify if it is an existing property
        # inside of it.
      } elseif (is_string ($alias) && $alias &&
        # Verify if it is an existing property
        # inside the $aliasList array class properrty
        isset (self::$aliasList [$alias]) &&
        # Make sure it is a callable object (Closure)
        # for using it as the handler for current command
        # alias.
        is_callable (self::$aliasList [$alias])) {
        return self::$aliasList [$alias];
      } elseif (is_string ($alias) && $alias &&
        # Make sure it is a valid command set
        # for using it as the handler for current
        # command alias.
        $handler = self::isCommandSet ($alias)) {
        return $handler;
      }
    }

    /**
     * @method string|null GetCommandPath
     *
     * Get a command file path given a command name.
     * Try finding it in the default command directory
     * first; so try matching the given name inside the
     * custom commands directory
     */
    public static function GetCommandPath ($command) {
      $cli = Context::GetContext ();

      $commandDirAlternates = $cli->commandsDirList;
      $command = join ('.', [$command, 'php']);
      $commandFileName = preg_replace (
        '/:{1,2}/',
        DIRECTORY_SEPARATOR,
        $command
      );

      foreach ($commandDirAlternates as $dir) {
        $commandPath = join ('/', [$dir, $commandFileName]);

        if (is_file ($commandPath)) {
          return realpath ($commandPath);
        }
      }
    }

    /**
     * @method string GetCommandNameFromPath
     *
     * Get the command reference name from a given
     * path string.
     *
     * The path name should be gotten stripping the
     * absolute directory name from the beggining of
     * the file absolute path.
     *
     * This'all be done by trying to match each command
     * directory inside the command directory list with
     * the beggining of the current command reference;
     * finding a reference for the given command name,
     * make sure that is the correct directory verifying
     * if the file name is found and so, get the command
     * reference inside that path.
     *
     * EG:
     *   $path = '\some\path\to\directory\context\command.php'
     *
     *   # Striping the command directory path...
     *
     *   [\some\path\to\directory\]context\command.php
     *
     *   # The file name after the directory avsolute path
     *   # should be used as the name for the current command
     *
     *   context\command.php
     *
     *   # without the php extension
     *
     *   context\command
     *
     * @param string $path
     *
     * The command absolute path.
     */
    public static function GetCommandNameFromPath ($path) {
      /**
       * Make sure the given path is a valid and non
       * emtpy string.
       */
      if (!!(is_string ($path) && $path)) {
        /**
         * map whole the command directory list
         */
        foreach (self::$commandDirectoryList as $dir) {
          $dirRe = path_to_regex ($dir);
          $dirRe = join ('', ['/^(', $dirRe, '(\/|\\\)*)/']);
          # /^(\/app\/user(\/|\\\)*)/
          if (@preg_match ($dirRe, $path)) {
            $fileName = preg_replace ($dirRe, '', $path);

            $fileRef = join (DIRECTORY_SEPARATOR, [
              $dir, $fileName
            ]);

            if (file_exists ($fileRef) &&
              realpath ($fileRef) === realpath ($path)) {
              return preg_replace (
                '/(\/|\\\)+/',
                ':',
                preg_replace ('/\.php$/i', '', $fileName)
              );
            }
          }
        }
      }
    }
  }}
}
