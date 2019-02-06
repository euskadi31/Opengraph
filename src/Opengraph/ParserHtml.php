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

use DOMDocument;
use RuntimeException;
use Opengraph\Opengraph;

class ParserHtml
{
    private $html;
    private $includeDefaults;
    
    public function __construct($html, $includeDefaults = false)
    {
        $this->html = $html;
        $this->includeDefaults = $includeDefaults;
    }

    /**
     * parse html tags
     *
     * @return Opengraph\Opengraph
     */
    public function parse()
    {
        $og = new Opengraph();

        $old_libxml_error = libxml_use_internal_errors(true);
        
        $dom = new DOMDocument;
        
        if(@$dom->loadHTML($this->html) === false) {
            throw new RuntimeException("Html is empty");
        }

        libxml_use_internal_errors($old_libxml_error);

        foreach($dom->getElementsByTagName('meta') as $tag) {
            if($this->includeDefaults && $tag->hasAttribute('name') && $tag->hasAttribute('content') && $tag->getAttribute('name') == 'description') {
                $og->addMeta('non-og-description', $tag->getAttribute('content'), $og::APPEND);
            } else if($tag->hasAttribute('property') && $tag->hasAttribute('content')) {
                $og->addMeta($tag->getAttribute('property'), $tag->getAttribute('content'), $og::APPEND);
            }
        }

        if($this->includeDefaults) {
            $titles = $dom->getElementsByTagName('title');
            if ($titles->length > 0) {
                $og->addMeta('non-og-title', $titles->item(0)->textContent, $og::APPEND);
            }
        }

        unset($dom);
        
        return $og;
    }
}