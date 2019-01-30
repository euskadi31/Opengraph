# Opengraph

[![Build Status](https://secure.travis-ci.org/euskadi31/Opengraph.png)](http://travis-ci.org/euskadi31/Opengraph)

## Test with [Atoum](https://github.com/atoum/atoum)

	cd Opengraph/
    curl -s https://getcomposer.org/installer | php
    php composer.phar install --dev
	./vendor/atoum/atoum/bin/atoum --glob Tests/Units/


## Writer

``` php
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

echo $og->render(new RenderHtml());
echo $og->render(new RenderJson());

?>
```

Output

``` html
    <meta property="og:title" content="The Rock" />
    <meta property="og:type" content="video.movie" />
    <meta property="og:url" content="http://www.imdb.com/title/tt0117500/" />
    <meta property="og:image" content="http://ia.media-imdb.com/images/rock.jpg" />

    {"og:title":"The Rock","og:type":"video.movie","og:url":"http:\/\/www.imdb.com\/title\/tt0117500\/","og:image":[{"og:image:url":"http:\/\/ia.media-imdb.com\/images\/rock.jpg"}]}
```

## Reader

``` php
<?php
namespace DemoParser;

require '../vendor/autoload.php';

use Opengraph;

$parser = new ParserHtml(file_get_contents('http://www.imdb.com/title/tt0117500/'));
$opengraph = $parser->parse();
print_r($opengraph->getArrayCopy());

?>
```

Output

Array
(
    [og:url] => http://www.imdb.com/title/tt0117500/
    [pageId] => tt0117500
    [pageType] => title
    [subpageType] => main
    [og:image] => Array
        (
            [0] => Array
                (
                    [og:image:url] => https://m.media-amazon.com/images/M/MV5BZDJjOTE0N2EtMmRlZS00NzU0LWE0ZWQtM2Q3MWMxNjcwZjBhXkEyXkFqcGdeQXVyNDk3NzU2MTQ@._V1_UY1200_CR90,0,630,1200_AL_.jpg
                )

        )

    [og:type] => video.movie
    [fb:app_id] => 115109575169727
    [og:title] => The Rock (1996)
    [og:site_name] => IMDb
    [og:description] => Directed by Michael Bay.  With Sean Connery, Nicolas Cage, Ed Harris, John Spencer. A mild-mannered chemist and an ex-con must lead the counterstrike when a rogue group of military men, led by a renegade general, threaten a nerve gas attack from Alcatraz against San Francisco.
)
