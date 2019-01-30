<?php

namespace Opengraph;

use Opengraph\Opengraph;

interface Render
{
    public function render(Opengraph $opengraph, array $configs = []);
}