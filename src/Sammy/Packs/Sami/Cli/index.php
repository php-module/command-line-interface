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

  Command::Register ('rundevserver', [
    'name' => function ($parameters, $options) {
      echo 'Dev Server Running', "\n\n\n Param => \n\n";

      print_r($options->only ('template', 'name'));

      print_r(Parameters::all ());

    },
    'aliases' => ['ds']
  ]);

  Command::Register ('helloman', [
    'name' => 'helloman',
    'aliases' => ['hl'],
    'handler' => function () {
      Console::Success ('Hello, Mannn..! Again');
    }
  ]);

  /**
   * Samils\Functions
   * @version 1.0
   * @author Sammy
   *
   * @keywords Samils, ils, ils-global-functions
   * ------------------------------------
   * - Autoload, application dependencies
   *
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   * ----
   * @Function Name: help
   * @Function Description: Get whole the command docs
   * @Function Args: $parameters
   */
  if (!function_exists ('Sammy\Packs\Sami\CommandLineInterface\help')) {
  /**
   * @version 1.0
   *
   * THE CURRENT ILS FUNCTION IS PROVIDED
   * TO AID THE DEVELOPMENT PROCESS IN ORDER
   * IT GET IN THE SAME WAY WHEN MOVING IT FROM
   * ANOTHER TO ANOTHER ENVIRONMENT.
   *
   * Note: on condition that this is an automatically
   * generated file, it should not be directly changed
   * without saving whole the changes into the original
   * repository source.
   *
   * @author Agostinho Sam'l
   * @keywords command-helper, cli-doc, help
   */
  Command::Register ('help', [
    'name' => 'help',
    'handler' => 'help',
    'description' => 'Doc the CLI',
    'aliases' => ['h']
  ]);
  function help ($parameters) {
    #print_r(['KJS']);

    #print_r(Parameters::last ());

    Console::Error ('Some thing is wrong..!!', "\n");

    Console::Warning ('HEHEHEHEHHE', "\n");

    Console::Success ('YEEEEEEEEEEEEEEYYYYYYYYY..!!!!!!!');
  }}

}
