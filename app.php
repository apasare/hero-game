#!/usr/bin/php
<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use SimpleCoding\Core\DependencyInjectionContainer as DIC;

$dic = DIC::getInstance();
$emagia = $dic->get('SimpleCoding\\Game');
$emagia->run([
    \SimpleCoding\Game::OPTION_CONFIG_FILE => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'config.yml'
]);
