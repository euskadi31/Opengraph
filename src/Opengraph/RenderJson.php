<?php

namespace Opengraph;

use Opengraph\Opengraph;

class RenderJson implements Render
{
    public function render(Opengraph $og, array $configs = [])
    {
        return json_encode($og->getArrayCopy());
    }
}