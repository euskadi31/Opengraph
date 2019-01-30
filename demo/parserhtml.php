<?php

namespace Demo;

require '../vendor/autoload.php';

use Opengraph\ParserHtml;

$parser = new ParserHtml(file_get_contents('http://www.imdb.com/title/tt0117500/'));
$opengraph = $parser->parse();

echo 'Parse from: http://www.imdb.com/title/tt0117500/';
echo '<br />';
print_r($opengraph->getArrayCopy());