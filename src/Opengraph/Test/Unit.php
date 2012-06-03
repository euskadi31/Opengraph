<?php
/**
 * @package     Opengraph
 * @author      Axel Etcheverry <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel Etcheverry (http://www.axel-etcheverry.com)
 * Displays     <a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license     http://creativecommons.org/licenses/MIT/deed.fr    MIT
 */

/**
 * @namespace
 */
namespace Opengraph\Test;

require_once __DIR__ . '/../../mageekguy.atoum.phar';

use \mageekguy\atoum;

abstract class Unit extends atoum\test 
{
    public function __construct(score $score = null, locale $locale = null, adapter $adapter = null)
    {
        $this->setTestNamespace('Tests\Units');

        parent::__construct($score, $locale, $adapter);
    }
}