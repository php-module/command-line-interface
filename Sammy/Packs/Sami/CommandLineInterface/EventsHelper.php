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
  use Closure;
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\Sami\CommandLineInterface\EventsHelper')) {
  /**
   * @trait EventsHelper
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
  trait EventsHelper {
    /**
     * @var array $handlers
     */
    private $handlers = [
      /**
       * A handler for calling before a
       * command run.
       */
      'before-run' => []
    ];

    /**
     * @method Sammy\Packs\Sami\CommandLineInterface beforeRun
     */
    public function beforeRun ($handler = null) {
      /**
       * Make sure the given handler is
       * a valid reference for a callable
       * object or function name.
       */
      if (!!is_callable ($handler)) {
        $this->handlers ['before-run'][] = $handler;
      }
    }

    /**
     * @method void trigger
     */
    public function trigger ($handlerList) {
      /**
       * Make sure the given handler list is
       * a non empty array.
       */
      if (is_array ($handlerList) && $handlerList) {
        $args = [];
        /**
         * Map the handler list to run each callable
         * inside it.
         *
         * It be a list of Closures or Sammy\Packs\Func
         * objects, in any of these cases, convert it to
         * a callable object by create a Sammy\Packs\Func
         * object from it... To apply it on the current
         * object context.
         */
        foreach ($handlerList as $i => $handler) {
          $handler = $this->eHandler ($handler);

          if (is_object ($handler)) {
            $handler->apply ($this, $args);
          } else {
            call_user_func_array ($handler, $args);
          }
        }
      } elseif (is_string ($handlerList) &&
        !empty ($handlerList) &&
        isset ($this->handlers [$handlerList]) &&
        is_array ($list = $this->handlers [$handlerList])) {
        $this->trigger ($list);
      }
    }

    private function eHandler ($handler) {
      if (is_string ($handler) &&
        !empty ($handler) &&
        is_callable ($handler)) {
        return $handler;
      }

      /**
       * vareify is the given handler is an object
       * and attempt to create a new Sammy\Packs\Func
       * from it.
       */
      if (is_object ($handler) && is_callable ($handler)) {
        return $handler instanceof Func ? $handler : new Func ($handler);
      }
    }
  }}
}
