<?php

namespace Facebook\Tests\Units;

require_once __DIR__ . '/../mageekguy.atoum.phar';
require_once __DIR__ . '/../../Opengraph.php';

use mageekguy\atoum;
use Facebook;

class Opengraph extends atoum\test
{
    public function __construct(score $score = null, locale $locale = null, adapter $adapter = null)
    {
        $this->setTestNamespace('Tests\Units');
        parent::__construct($score, $locale, $adapter);
    }
    
    public function testOpengraph()
    {
        $og = new Facebook\Opengraph();
        $og->append(Facebook\Opengraph::OG_TITLE, 'test');
        
        $this->assert->string($og->getMeta(Facebook\Opengraph::OG_TITLE))->isEqualTo('test');
        $this->assert->string((string)$og)->isEqualTo("\t" . '<meta property="og:title" content="test" />' . PHP_EOL);
        $this->assert->object($og->addMeta(Facebook\Opengraph::OG_TYPE, 'article', Facebook\Opengraph::APPEND))->isInstanceOf('\Facebook\Opengraph');
        $this->assert->object($og->append(Facebook\Opengraph::OG_TYPE, 'article'))->isInstanceOf('\Facebook\Opengraph');
        $this->assert->object($og->prepend(Facebook\Opengraph::OG_IMAGE, 'http://www.google.com/'))->isInstanceOf('\Facebook\Opengraph');
    }
    
    /*public function testException()
    {
         $og = new Facebook\Opengraph();
         
         $this->assert->exception(function() use ($og) {
             $og->addMeta(Facebook\Opengraph::OG_TYPE, 'test', Facebook\Opengraph::APPEND);
         })
         ->isInstanceOf('\InvalidArgumentException');
    }*/
    
    
    public function testParser()
    {
        $html = '<html prefix="og: http://ogp.me/ns#">
        <head>
        <title>The Rock (1996)</title>
        <meta property="og:title" content="The Rock" />
        <meta property="og:type" content="video.movie" />
        <meta property="og:url" content="http://www.imdb.com/title/tt0117500/" />
        <meta property="og:image" content="http://ia.media-imdb.com/images/rock.jpg" />
        </head>
        <body></body>
        </html>';
        
        $og = new Facebook\Opengraph();
        print_r($og->parse($html));
        
    }
}