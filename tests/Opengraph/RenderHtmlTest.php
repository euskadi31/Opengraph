<?php

use PHPUnit\Framework\TestCase,
    Opengraph\RenderHtml,
    Opengraph\Opengraph;

class RenderHtmlTest extends TestCase
{
    public function testRenderShouldReturnHtml()
    {
        $configs = [
            'indent' => "\t", 
            'eol'    => PHP_EOL
        ];

        $html = $configs['indent'] . '<meta property="og:title" content="The Rock" />' . $configs['eol']
            . $configs['indent'] . '<meta property="og:type" content="video.movie" />' . $configs['eol']
            . $configs['indent'] . '<meta property="og:url" content="http://www.imdb.com/title/tt0117500/" />' . $configs['eol']
            . $configs['indent'] . '<meta property="og:image" content="http://ia.media-imdb.com/images/rock.jpg" />' . $configs['eol'];

        $og = new Opengraph();
    	$og->addMeta(Opengraph::OG_TITLE, 'The Rock');
		$og->addMeta(Opengraph::OG_TYPE, Opengraph::TYPE_VIDEO_MOVIE);
		$og->addMeta(Opengraph::OG_URL, 'http://www.imdb.com/title/tt0117500/');
		$og->addMeta(Opengraph::OG_IMAGE, 'http://ia.media-imdb.com/images/rock.jpg');
        
		$this->assertEquals($html, $og->render(new RenderHtml(), $configs));
    }
}