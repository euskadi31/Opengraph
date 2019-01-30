<?php

use PHPUnit\Framework\TestCase,
    Opengraph\RenderJson,
    Opengraph\Opengraph;

class RenderJsonTest extends TestCase
{
    public function testRenderShouldReturnJson()
    {
        $json = '{"og:title":"The Rock","og:type":"video.movie","og:url":"http:\/\/www.imdb.com\/title\/tt0117500\/","og:image":[{"og:image:url":"http:\/\/ia.media-imdb.com\/images\/rock.jpg"}]}';

        $og = new Opengraph();
    	$og->addMeta(Opengraph::OG_TITLE, 'The Rock');
		$og->addMeta(Opengraph::OG_TYPE, Opengraph::TYPE_VIDEO_MOVIE);
		$og->addMeta(Opengraph::OG_URL, 'http://www.imdb.com/title/tt0117500/');
		$og->addMeta(Opengraph::OG_IMAGE, 'http://ia.media-imdb.com/images/rock.jpg');

		$this->assertEquals($json, $og->render(new RenderJson()));
    }
}