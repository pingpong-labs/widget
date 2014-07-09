<?php

$loader = require __DIR__ . "/../vendor/autoload.php";
$loader->add('Widget\\', 'tests');

putenv('SKIP_BLADE=1');
