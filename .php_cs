<?php

return PhpCsFixer\Config::create()
->setRules([
    '@PSR2' => true,
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'no_multiline_whitespace_before_semicolons' => true,
    'no_useless_return' => true,
]);
