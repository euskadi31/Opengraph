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

class WriterTest extends TestCase
{
    public function testClass()
    {
        $writer = new Opengraph\Writer();

        $this->assertInstanceOf('\Opengraph\Opengraph', $writer);
        $this->assertInstanceOf('\Iterator', $writer);
        $this->assertInstanceOf('\Serializable', $writer);
        $this->assertInstanceOf('\Countable', $writer);
    }

    public function testWriter()
    {
        $writer = new Opengraph\Writer();
        $writer->append(Opengraph\Writer::OG_TITLE, 'test');

        $this->assertEquals("\t" . '<meta property="og:title" content="test" />' . PHP_EOL, $writer->render());

        $this->assertInstanceOf('\Opengraph\Writer', $writer->addMeta(
            Opengraph\Writer::OG_TYPE,
            Opengraph\Writer::TYPE_WEBSITE,
            Opengraph\Writer::APPEND
        ));

        $this->assertInstanceOf('\Opengraph\Writer', $writer->append(Opengraph\Writer::OG_TYPE, Opengraph\Writer::TYPE_WEBSITE));

        $this->assertInstanceOf('\Opengraph\Writer', $writer->prepend(Opengraph\Writer::OG_IMAGE, 'http://www.google.com/'));

        $this->assertInstanceOf('\ArrayObject', $writer->getMetas());

        $this->assertEquals('d2d171c74585f44ee53ba4c351ba0416', md5($writer->serialize()));

        $this->assertEquals(3, $writer->count());

        $this->assertInstanceOf('\Opengraph\Meta', $writer->current());

        $this->assertEquals(0, $writer->key());

        $writer->next();

        $this->assertEquals(1, $writer->key());

        $this->assertTrue($writer->valid());

        $writer->next();
        $writer->next();
        $writer->next();

        $this->assertFalse($writer->valid());

        $writer->rewind();

        $this->assertEquals(0, $writer->key());

        $writer->unserialize('C:11:"ArrayObject":751:{x:i:0;a:6:{i:0;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:6:"og:url";s:11:" * _content";s:36:"http://www.imdb.com/title/tt0117500/";}i:1;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:8:"og:title";s:11:" * _content";s:11:"Rock (1996)";}i:2;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:7:"og:type";s:11:" * _content";s:11:"video.movie";}i:3;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:8:"og:image";s:11:" * _content";s:99:"http://ia.media-imdb.com/images/M/MV5BMTM3MTczOTM1OF5BMl5BanBnXkFtZTYwMjc1NDA5._V1._SX98_SY140_.jpg";}i:4;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:12:"og:site_name";s:11:" * _content";s:4:"IMDb";}i:5;O:14:"Opengraph\Meta":2:{s:12:" * _property";s:9:"fb:app_id";s:11:" * _content";s:15:"115109575169727";}};m:a:0:{}}');

        $this->assertEquals(6, $writer->count());

        $this->assertEquals([
            'og:url' => 'http://www.imdb.com/title/tt0117500/',
            'og:title' => 'Rock (1996)',
            'og:type' => 'video.movie',
            'og:image' => [
                0 => [
                    'og:image:url' => 'http://ia.media-imdb.com/images/M/MV5BMTM3MTczOTM1OF5BMl5BanBnXkFtZTYwMjc1NDA5._V1._SX98_SY140_.jpg'
                ]
            ],
            'og:site_name' => 'IMDb',
            'fb:app_id' => '115109575169727'
        ], $writer->getArrayCopy());

        $this->assertEquals('Rock (1996)', $writer->getMeta(Opengraph\Writer::OG_TITLE));

        $this->assertTrue($writer->hasMeta(Opengraph\Writer::OG_TITLE));

        $this->assertTrue($writer->removeMeta(Opengraph\Writer::OG_TITLE));

        $this->assertEquals(5, $writer->count());

        $this->assertFalse($writer->removeMeta(Opengraph\Writer::OG_TITLE));

        $this->assertFalse($writer->getMeta(Opengraph\Writer::OG_TITLE));

        $this->assertFalse($writer->hasMeta(Opengraph\Writer::OG_TITLE));

        $writer->addMeta(Opengraph\Writer::OG_TYPE, Opengraph\Writer::TYPE_BOOK, Opengraph\Writer::APPEND);

        $this->assertEquals(Opengraph\Writer::TYPE_BOOK, $writer->getMeta(Opengraph\Writer::OG_TYPE));

        $writer = new Opengraph\Writer();
        $this->assertEquals(5, $writer->count());

        $writer = new Opengraph\Writer();
        $writer->clear();

        $this->assertEquals(0, $writer->count());

        $writer->append(Opengraph\Writer::FB_ADMINS, 12345567657868);

        $this->assertEquals("\t" . '<meta property="fb:admins" content="12345567657868" />' . PHP_EOL, $writer->render());

        $writer->clear();

        $writer->append(Opengraph\Writer::FB_ADMINS, '12345567657868,23334543656456');
        $this->assertEquals("\t" . '<meta property="fb:admins" content="12345567657868,23334543656456" />' . PHP_EOL, $writer->render());
    }
}
