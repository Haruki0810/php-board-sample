<?php
set_include_path(__DIR__ . '/../');
require 'vendor/autoload.php';

use eftec\bladeone\BladeOne;

$profile = [
    'name' => '鴨川桂',
    'age' => 20,
];

$blade = new BladeOne(__DIR__ . '/../src/views',__DIR__ . '/../cache');
echo $blade->run('profile', ['profile' => $profile]);