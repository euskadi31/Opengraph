<?php

namespace Opengraph;

use Opengraph\Opengraph;

class RenderHtml implements Render
{
    public function render(Opengraph $og, array $configs = [])
    {
        if ( ! $configs) {
            $configs = [
                'eol'    => PHP_EOL,
                'indent' => "\t"
            ];
        }
 
        $html = '';
        foreach($og->getMetas() as $meta) {
            $html .= $configs['indent'] . $meta->render() . $configs['eol'];
        }
        
        return $html;
    }
}