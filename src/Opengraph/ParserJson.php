<?php
/**
 * @package     Opengraph
 * @author      Bruno Pereira <brunomarcospereira@gmail.com>
 * @license     http://creativecommons.org/licenses/MIT/deed.fr    MIT
 */

/**
 * @namespace
 */
namespace Opengraph;

use DOMDocument;
use RuntimeException;
use Opengraph\Opengraph;

class ParserJson
{
    private $json;
    private $includeDefaults;
    
    public function __construct($json, $includeDefaults = false)
    {
        $this->json = $json;
        $this->includeDefaults = $includeDefaults;
    }

    /**
     * parse json
     *
     * @return Opengraph\Opengraph
     */
    public function parse()
    {
        $jsonDecoded = json_decode($this->json, true);
        
        if ( ! $jsonDecoded) {
            throw new RuntimeException("Json is empty");
        }

        $og = new Opengraph();
        array_walk_recursive($jsonDecoded, function($v, $k) use ($og) {
            $og->addMeta($k, $v);
        });

        return $og;
    }
}