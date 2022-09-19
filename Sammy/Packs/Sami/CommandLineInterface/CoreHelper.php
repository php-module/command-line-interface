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
  use Sammy\Packs\Func;
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\CoreHelper')) {
  /**
   * @trait CoreHelper
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
  trait CoreHelper {
    private function executeClassCommand ($class, $options) {
      list ($method, $args) = $options;

      if (class_exists ($class)) {
        if (method_exists ($class, $method)) {
          forward_static_call_array (
            [$class, $method], $args
          );

          return true;
        } else {
          $classObject = new $class;

          if (method_exists ($classObject, $method)) {
            call_user_func_array ([$classObject, $method], $args);

            return true;
          }
        }
      }
    }

    private function evaluate ($value) {
      $booleanFalseValues = preg_split('/\s+/',
        'false off no'
      );

      $booleanTrueValues = preg_split('/\s+/',
        'true on yes'
      );

      $booleanValues = array_merge (
        $booleanTrueValues,
        $booleanFalseValues
      );

      if (is_numeric ($value)) {
        return ( float )($value);
      } elseif (in_array (strtolower ($value), $booleanValues)) {
        return in_array (strtolower ($value), $booleanTrueValues);
      }

      return empty ($value) ? true : $value;
    }

    private function parseArgsList ($args) {
      $parameteres = array ();
      $options = array ();

      if (is_string ($args)) {
        $args = preg_split ('/\s+/', $args);
      }

      $optionRe = '/^-{1,3}([a-zA-Z0-9_\-]+)=?/';

      for ($i = 0; $i < count ($args); $i++) {
        $arg = $args [ $i ];

        if (preg_match ($optionRe, $arg, $match)) {
          //
          // --type | ---type
          //
          if (preg_match ('/^(-{2,3})/', $arg)) {
            $value = true;

            if (preg_match ('/=$/', $match[0])) {
              $value = preg_replace ($optionRe, '', $arg);
            }

            $options [$match [1]] = $this->evaluate ($value);
          } else {
            $value = preg_replace ($optionRe, '', $arg);

            if (!preg_match ('/=$/', $match [0])) {
              $value = true;
            }

            $currentMatch = $match [ 1 ];
            $currentMatchValue = $this->evaluate ( $value );
            for ($currentMatchCharIndex = 0; $currentMatchCharIndex < strlen($currentMatch); $currentMatchCharIndex++) {
              $options [$currentMatch [$currentMatchCharIndex]] = $currentMatchValue;
            }
          }
        } else {
          array_push ($parameteres, $arg);
        }
      }

      #Parameters::setDataObjectBase ($parameteres);
      #Options::setDataObjectBase ($options);

      return array (
        'parameteres' => $parameteres,
        'options' => $options
      );
    }

    private function runCommand ($command, $prefix, $arguments) {
      $re = '/(::([a-zA-Z0-9_]+))$/';

      $args = $this->parseArgsList ($arguments);
      $parameteres = new Parameters ($args ['parameteres']);
      $options = new Options ($args ['options']);

      $argList = [$parameteres, $options];

      if (is_object ($command) && is_callable ($command)) {
        $command = new Func ($command);
        $command->apply ($this, $argList);
        # call_user_func_array ($command, $argList);

        return true;
      } elseif (is_string ($command) && preg_match ($re, $command, $match)) {
        $command = preg_replace ($re, '', $command);
        $command = join ('', [$prefix, $command]);

        $commandOutput = $this->executeClassCommand (
          $command, [$match [2], $argList]
        );

        if ($commandOutput) {
          return true;
        }
      } elseif (!(is_string ($command) && $command)) {
        return false;
      }

      $commandPath = preg_replace ('/:{1,2}/', '\\', $command);

      if (empty ($prefix)) {
        $prefix = self::DEFAULT_NAMESPACE;
      }

      $commandPath = join ('', [$prefix, $commandPath]);

      if (is_string ($prefix) && !empty ($prefix) &&
        function_exists ($commandPath)) {
        call_user_func_array ($commandPath, $argList);

        return true;
      }

      $commandFilePath = Command::GetCommandPath ($command);

      ## join ($ds, [$this->commandsDir, $commandFilePath . '.php'])

      $commandFileName = pathinfo ($commandFilePath, 8);
      $commandModule = requires ($commandFilePath);

      if ($handler = Command::IsCommandArray ($commandModule)) {
        $handler = new Func ($handler);
        $handler->apply ($this, $argList);
        # call_user_func_array ($handler, $argList);
        return true;
      }
    }
  }}
}
