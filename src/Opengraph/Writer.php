<?php
/**
 * @package     Opengraph
 * @author      Axel Etcheverry <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel Etcheverry (http://www.axel-etcheverry.com)
 * Displays     <a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license     http://creativecommons.org/licenses/MIT/deed.fr    MIT
 */

/**
 * @namespace
 */
namespace Opengraph;

class Writer extends Opengraph
{
    /**
     * @var \ArrayObject
     */
    protected static $storage;
    
    /**
     * @var Integer
     */
    //protected static $position;
    
    /**
     * Append meta
     * 
     * @param String $property
     * @param String $content
     * @return \Opengraph\Opengraph
     */
    public function append($property, $content)
    {
        return $this->addMeta($property, $content, self::APPEND);
    }

    /**
     * Prepend meta
     * 
     * @param String $property
     * @param String $content
     * @return \Opengraph\Opengraph
     */
    public function prepend($property, $content)
    {
        return $this->addMeta($property, $content, self::PREPEND);
    }
    
    /**
     * Write Opengraph from Json
     * 
     * @param String $json
     * @return \Opengraph\Writer return a new Writer
     */
    public static function fromJson($json)
    {
        $jsonDecoded = json_decode($json, true);

        $writer = new Writer();

        array_walk_recursive($jsonDecoded, function($v, $k) use ($writer) {
            $writer->append($k, $v);
        });

        return $writer;
    }

    /**
     * Render all meta tags
     * 
     * @return String
     */
    public function render($indent = "\t")
    {
        $html = '';
        foreach(self::$storage as $meta) {
            $html .= $indent . $meta->render() . PHP_EOL;
        }
        
        return $html;
    }
}
