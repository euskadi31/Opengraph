<?php

namespace Demo;

require '../vendor/autoload.php';

use Opengraph\Opengraph,
	Opengraph\RenderHtml,
	Opengraph\RenderJson;

$og = new Opengraph();
$og->addMeta(Opengraph::OG_TITLE, 'The Rock');
$og->addMeta(Opengraph::OG_TYPE,  Opengraph::TYPE_VIDEO_MOVIE);
$og->addMeta(Opengraph::OG_URL,   'http://www.imdb.com/title/tt0117500/');
$og->addMeta(Opengraph::OG_IMAGE, 'http://ia.media-imdb.com/images/rock.jpg');

echo '<h2>HTML Output</h2>';
echo '<pre>';
echo htmlentities($og->render(new RenderHtml()));
echo '</pre>';
echo '<br />';

echo '<h2>JSON Output</h2>';
echo '<pre>';
echo $og->render(new RenderJson());
echo '</pre>';