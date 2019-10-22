<?php

namespace Huangdijia\WebSoar\OutputModifiers;

interface OutputModifier
{
    public function modify(string $output = ''): string;
}
