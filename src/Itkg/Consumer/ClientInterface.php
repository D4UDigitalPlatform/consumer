<?php

namespace Itkg\Consumer;

interface ClientInterface
{
    public function init(Request $request);
    public function getResponse();
}