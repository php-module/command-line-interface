<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Sami
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
namespace Sammy\Packs\Sami {
  use Sammy\Packs\Sami\CommandLineInterface\Parameters;
  use Sammy\Packs\Sami\CommandLineInterface\Options;
  use Sammy\Packs\Sami\CommandLineInterface\Command;
  use Sammy\Packs\Sami\CommandLineInterface\Console;
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!class_exists ('Sammy\Packs\Sami\CommandLineInterface')) {
  /**
   * @class CommandLineInterface
   * Base internal class for the
   * Sami module.
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
  class CommandLineInterface {
    use CommandLineInterface\Base;
    use CommandLineInterface\CoreHelper;
    use CommandLineInterface\EventsHelper;
    use CommandLineInterface\PluginsHelper;
    use CommandLineInterface\CommandSetHelper;

    /**
     * DEFAULT_NAMESPACE
     */
    const DEFAULT_NAMESPACE = 'Sammy\Packs\Sami\CommandLineInterface\\';

    public function ___execute ($argv = null) {
      $args = func_get_args ();

      $argv = count ($args) >= 2 ? $args [0] : [];

      if (!(is_array ($argv) && count ($argv) >= 1)) {
        $argv = $_SERVER ['argv'];
      }

      $args = array_slice ($argv, 2, count ($argv));
      $command = isset ($argv [1]) ? $argv [1] : '';

      return $this->___run ($command, $args, [
        ['__clinterObjectContext' => $this]
      ]);
    }

    protected function commandPrefix ($command) {
      if (!is_string ($command)) {
        return;
      }

      $commandPrefix = preg_replace ('/(\\\)+$/', '', $this->namespace) . '\\';

      $commandPrefixList = [
        self::DEFAULT_NAMESPACE, $commandPrefix
      ];

      foreach ($commandPrefixList as $prefix) {
        $commandPath = join ('', [$prefix, $command]);

        if (function_exists ($commandPath)) {
          return $prefix;
        }
      }

      return false;
    }

    /**
     * @method void runRaw
     */
    public function ___runRaw ($command = null) {
      if (is_string ($command)) {
        $command = preg_split ('/\s+/', $command);
      }

      if (!(is_array ($command) &&
        count ($command) >= 1 &&
        is_string ($command [0]))) {
        return;
      }

      $commandName = $command [0];
      $commandArgs = array_slice ($command, 1, count ($command));

      return $this->___run ($commandName, $commandArgs);
    }

    /**
     * @method void run
     */
    public function ___run ($command, $args = []) {

      if (is_string ($args)) {
        $args = preg_split ('/\s+/', $args);
      }

      $this->trigger ('before-run');

      #exit (self::moduleDir () . '/commands');

      # run command with given alternate
      $prefix = $this->commandPrefix ($command);

      /**
       * Verify if ...
       */
      if ($this->runCommand ($command, $prefix, $args)) {
        return 0;
      } elseif ($alias = Command::Alias ($command)) {
        # Avoid recursive pointers
        # EG:
        # h -> h
        # help -> h
        # h -> help
        # ...
        return $this->___run ($alias, $args);
      } elseif (Command::Exists ($command)) {
        $cmd = new Command ($command);

        if ((is_string ($cmd->handler) &&
          $this->commandPrefix ($cmd->handler)) ||
          is_callable ($cmd->handler)) {
          return $this->___run ($cmd->handler, $args);
        }

      } elseif ($script = $this->script ($command)) {
        $scriptKeys = preg_split ('/\s+/', $script);

        $scriptArgs = array_slice ($scriptKeys, 1, count ($scriptKeys));

        $scriptArgs = array_merge ($scriptArgs, $args);

        return $this->___run ($scriptKeys [0], $scriptArgs);
      }

      Console::Error ("\nUncought", $command);
      echo "\nArguments: ";
      print_r ($args);
      exit ("\n\n");
    }
  }}
}
