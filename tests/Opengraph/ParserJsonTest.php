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
    Opengraph\ParserJson;

class ParserJsonTest extends TestCase
{
    public function testParseException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Json is empty');

        $parser = new ParserJson('');
        $parser->parse();
    }

    public function testParse()
    {
        $og1 = new Opengraph();
        $og1->addMeta(Opengraph::OG_TITLE, 'The Rock');
        $og1->addMeta(Opengraph::OG_TYPE,  Opengraph::TYPE_VIDEO_MOVIE);
        $og1->addMeta(Opengraph::OG_URL,   'http://www.imdb.com/title/tt0117500/');
        $og1->addMeta(Opengraph::OG_IMAGE, 'http://ia.media-imdb.com/images/rock.jpg');

        $json = json_encode($og1->getArrayCopy());

        $parser = new ParserJson($json);
        $og2 = $parser->parse();

        $this->assertInstanceOf('\Opengraph\Opengraph', $og2);

        $this->assertEquals(4, $og2->count());

        $this->assertEquals($og1->getArrayCopy(), $og2->getArrayCopy());
    }
}
