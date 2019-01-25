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

class Reader extends TestCase
{
    public function testClass()
    {
        $reader = new Opengraph\Reader();

        $this->assertInstanceOf('\Opengraph\Opengraph', $reader);
        $this->assertInstanceOf('\Iterator', $reader);
        $this->assertInstanceOf('\Serializable', $reader);
        $this->assertInstanceOf('\Countable', $reader);
    }

    public function testReaderParseException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Contents is empty');

        $reader = new Opengraph\Reader();
        $reader->parse('');
    }

    public function testReader()
    {
        $html = '<meta property="og:url" content="http://www.imdb.com/title/tt1074638/" />
        <meta property="og:title" content="Skyfall (2012)"/>
        <meta property="og:type" content="video.movie"/>
        <meta property="og:image" content="http://ia.media-imdb.com/images/M/MV5BMTczMjQ5NjE4NV5BMl5BanBnXkFtZTcwMjk0NjAwNw@@._V1._SX95_SY140_.jpg"/>
        <meta property="og:site_name" content="IMDb"/>
        <meta property="fb:app_id" content="115109575169727"/>';

        $reader = new Opengraph\Reader();

        $this->assertInstanceOf('\Opengraph\Reader', $reader);

        $reader->parse($html);

        $this->assertEquals(6, $reader->count());

        $this->assertEquals([
            'og:url' => 'http://www.imdb.com/title/tt1074638/',
            'og:title' => 'Skyfall (2012)',
            'og:type' => 'video.movie',
            'og:image' => [
                0 => [
                    'og:image:url' => 'http://ia.media-imdb.com/images/M/MV5BMTczMjQ5NjE4NV5BMl5BanBnXkFtZTcwMjk0NjAwNw@@._V1._SX95_SY140_.jpg',
                ],
            ],
            'og:site_name' => 'IMDb',
            'fb:app_id' => '115109575169727',
        ], $reader->getArrayCopy());

        $this->assertInstanceOf('\ArrayObject', $reader->getMetas());

        $this->assertInstanceOf('\Opengraph\Meta', $reader->current());

        $this->assertEquals(0, $reader->key());

        $reader->next();

        $this->assertEquals(1, $reader->key());

        $this->assertTrue($reader->valid());

        $reader->next();
        $reader->next();
        $reader->next();
        $reader->next();
        $reader->next();

        $this->assertFalse($reader->valid());

        $reader->rewind();

        $this->assertEquals(0, $reader->key());

        $html = '<title>Skyfall Title</title>
        <meta property="og:url" content="http://www.imdb.com/title/tt1074638/" />
        <meta property="og:title" content="Skyfall (2012)"/>
        <meta property="og:type" content="video.movie"/>
        <meta property="og:image" content="http://ia.media-imdb.com/images/M/MV5BMTczMjQ5NjE4NV5BMl5BanBnXkFtZTcwMjk0NjAwNw@@._V1._SX95_SY140_.jpg"/>
        <meta property="og:site_name" content="IMDb"/>
        <meta name="description" content="Skyfall meta description" />
        <meta property="og:description" content="Skyfall og description"/>';

        $reader = new Opengraph\Reader();

        $this->assertInstanceOf('\Opengraph\Reader', $reader);

        $reader->parse($html, true);

        $this->assertEquals(8, $reader->count());

        $this->assertEquals([
            'og:url' => 'http://www.imdb.com/title/tt1074638/',
            'og:title' => 'Skyfall (2012)',
            'og:type' => 'video.movie',
            'og:image' => [
                0 => [
                    'og:image:url' => 'http://ia.media-imdb.com/images/M/MV5BMTczMjQ5NjE4NV5BMl5BanBnXkFtZTcwMjk0NjAwNw@@._V1._SX95_SY140_.jpg',
                ],
            ],
            'og:site_name' => 'IMDb',
            'non-og-description' => 'Skyfall meta description',
            'og:description' => 'Skyfall og description',
            'non-og-title' => 'Skyfall Title'
        ], $reader->getArrayCopy());

        $reader = new Opengraph\Reader();

        $reader->parse($html, false);

        $this->assertEquals(6, $reader->count());

        $this->assertEquals([
            'og:url' => 'http://www.imdb.com/title/tt1074638/',
            'og:title' => 'Skyfall (2012)',
            'og:type' => 'video.movie',
            'og:image' => [
                0 => [
                    'og:image:url' => 'http://ia.media-imdb.com/images/M/MV5BMTczMjQ5NjE4NV5BMl5BanBnXkFtZTcwMjk0NjAwNw@@._V1._SX95_SY140_.jpg',
                ],
            ],
            'og:site_name' => 'IMDb',
            'og:description' => 'Skyfall og description'
        ], $reader->getArrayCopy());
    }
}
