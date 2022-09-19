<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Sami\Cli
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
namespace Sammy\Packs\Sami\Cli {
  use Sammy\Packs\Sami\CommandLineInterface\Console;
  use Sammy\Packs\Sami\CommandLineInterface\Options;
  use Sammy\Packs\Sami\CommandLineInterface\Template;
  use Sammy\Packs\Sami\CommandLineInterface\Parameters;
  /**
   * Server Command
   */
  $module->exports = [
    'name' => 'template:draw',
    'description' => 'Draw a tamplete file',
    'aliases' => [],
    'handler' => function (Parameters $parameters, Options $options) {
      $template = $parameters->first;


      $props = base64_decode ($options->props);

      #exit ($props);
      $props = (array)json_decode ($props);

      #print_r($props);

      #exit (0);
      if (!$props) {
        $props = [];
      }

      #exit ('T => ' . $template);

      $templatePath = Template::GetTemplatePath ($template);

      #$templatePath = realpath (join ('/', [$this->templatesDir, $template . '.template.' . $templateFileExtension]));

      if (!$templatePath) {
        exit ('MAAAAAUUUU => ' . $template);
        return false;
      }

      $templateContext = function ($props) {
        if (in_array (pathinfo ($props ['__target'], 4), ['php'])) {
          echo "<?php\n";
        }
        #print_r($props);
        #exit ("\n\n\n". 'T => ' . $props ['__template']);
        include_once $props ['__template'];
      };

      $templateContext (array_merge ($props, ['__template' => $templatePath]));

      exit (0);
    }
  ];
}
