<?php

namespace Application;

require_once __DIR__ . '/../library/Opengraph/Meta.php';
require_once __DIR__ . '/../library/Opengraph/Opengraph.php';
require_once __DIR__ . '/../library/Opengraph/Writer.php';

use Opengraph;

$writer = new Opengraph\Writer();
$writer->append(Opengraph\Writer::OG_TITLE, 'test');
$writer->append(Opengraph\Writer::OG_TYPE, Opengraph\Writer::TYPE_WEBSITE);
$writer->append(Opengraph\Writer::OG_DESCRIPTION, 'bla bla bla');
$writer->append(Opengraph\Writer::OG_IMAGE, 'http://www.google.com/logo.jpg');
$writer->append(Opengraph\Writer::OG_IMAGE, 'http://www.google.com/logo2.jpg');

file_put_contents('writer.html', $writer->render());

echo file_get_contents('writer.html') . PHP_EOL;