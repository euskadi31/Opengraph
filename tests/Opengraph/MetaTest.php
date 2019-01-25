<?php
/*
 * This file is part of the RedisBundle package.
 *
 * (c) Axel Etcheverry <axel@etcheverry.biz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    public function testMeta()
    {
        $meta = new Opengraph\Meta(Opengraph\Opengraph::OG_TITLE, 'test');

        $this->assertInstanceOf('\Opengraph\Meta', $meta);

        $this->assertEquals('og:title', $meta->getProperty());

        $this->assertEquals('test', $meta->getContent());

        $this->assertEquals('<meta property="og:title" content="test" />', $meta->render());


        $meta->setProperty(Opengraph\Opengraph::OG_TYPE);

        $this->assertEquals('og:type', $meta->getProperty());

        $meta->setContent(Opengraph\Opengraph::TYPE_BOOK);

        $this->assertEquals('book', $meta->getContent());


        $this->assertEquals('<meta property="og:type" content="book" />', $meta->render());

        $meta->setContent(array(123, 456));

        $this->assertEquals('<meta property="og:type" content="123,456" />', $meta->render());
    }
}
