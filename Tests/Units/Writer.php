<?php

namespace Opengraph\Tests\Units;

require_once __DIR__ . '/../../library/Opengraph/Test/Unit.php';
require_once __DIR__ . '/../../library/Opengraph/Meta.php';
require_once __DIR__ . '/../../library/Opengraph/Opengraph.php';
require_once __DIR__ . '/../../library/Opengraph/Writer.php';

use Opengraph;

class Writer extends Opengraph\Test\Unit
{
    public function testWriter()
    {
        $writer = new Opengraph\Writer();
        
        $writer->append(Opengraph\Writer::OG_TITLE, 'test');
        
        $this->assert->string($writer->render())->isEqualTo("\t" . '<meta property="og:title" content="test" />' . PHP_EOL);
        $this->assert->object($writer->addMeta(Opengraph\Writer::OG_TYPE, Opengraph\Writer::TYPE_WEBSITE, Opengraph\Writer::APPEND))->isInstanceOf('\Opengraph\Writer');
        $this->assert->object($writer->append(Opengraph\Writer::OG_TYPE, Opengraph\Writer::TYPE_WEBSITE))->isInstanceOf('\Opengraph\Writer');
        $this->assert->object($writer->prepend(Opengraph\Writer::OG_IMAGE, 'http://www.google.com/'))->isInstanceOf('\Opengraph\Writer');
    }
}