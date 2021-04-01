<?php

function transforms($data, $transformer = null)
{
    return new \LukeVear\LaravelTransformer\TransformerEngine($data, $transformer);
}
