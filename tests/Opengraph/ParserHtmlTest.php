<?php
/*
 * This file is part of the RedisBundle package.
 *
 * (c) Axel Etcheverry <axel@etcheverry.biz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\TestCase,
    Opengraph\Opengraph,
    Opengraph\ParserHtml;

class ParserHtmlTest extends TestCase
{
    public function testParseException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Html is empty');

        $parser = new ParserHtml('');
        $parser->parse();
    }

    public function testParse()
    {
        $html = '<meta property="og:url" content="http://www.imdb.com/title/tt1074638/" />
        <meta property="og:title" content="Skyfall (2012)"/>
        <meta property="og:type" content="video.movie"/>
        <meta property="og:image" content="http://ia.media-imdb.com/images/M/MV5BMTczMjQ5NjE4NV5BMl5BanBnXkFtZTcwMjk0NjAwNw@@._V1._SX95_SY140_.jpg"/>
        <meta property="og:site_name" content="IMDb"/>
        <meta property="fb:app_id" content="115109575169727"/>';

        $parser1 = new ParserHtml($html);
        $opengraph1 = $parser1->parse();

        $this->assertInstanceOf('\Opengraph\Opengraph', $opengraph1);

        $this->assertEquals(6, $opengraph1->count());

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
        ], $opengraph1->getArrayCopy());

        $this->assertInstanceOf('\ArrayObject', $opengraph1->getMetas());

        $this->assertInstanceOf('\Opengraph\Meta', $opengraph1->current());

        $this->assertEquals(0, $opengraph1->key());

        $opengraph1->next();

        $this->assertEquals(1, $opengraph1->key());

        $this->assertTrue($opengraph1->valid());

        $opengraph1->next();
        $opengraph1->next();
        $opengraph1->next();
        $opengraph1->next();
        $opengraph1->next();

        $this->assertFalse($opengraph1->valid());

        $opengraph1->rewind();

        $this->assertEquals(0, $opengraph1->key());

        $html = '<title>Skyfall Title</title>
        <meta property="og:url" content="http://www.imdb.com/title/tt1074638/" />
        <meta property="og:title" content="Skyfall (2012)"/>
        <meta property="og:type" content="video.movie"/>
        <meta property="og:image" content="http://ia.media-imdb.com/images/M/MV5BMTczMjQ5NjE4NV5BMl5BanBnXkFtZTcwMjk0NjAwNw@@._V1._SX95_SY140_.jpg"/>
        <meta property="og:site_name" content="IMDb"/>
        <meta name="description" content="Skyfall meta description" />
        <meta property="og:description" content="Skyfall og description"/>';

        $parser2 = new ParserHtml($html, true);
        $opengraph2 = $parser2->parse();

        $this->assertInstanceOf('\Opengraph\Opengraph', $opengraph2);

        $this->assertEquals(8, $opengraph2->count());

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
        ], $opengraph2->getArrayCopy());

        $parser3 = new ParserHtml($html, false);
        $opengraph3 = $parser3->parse();

        $this->assertEquals(6, $opengraph3->count());

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
        ], $opengraph3->getArrayCopy());
    }
}
