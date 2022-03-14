<?php

$f3 = require(__DIR__.'/vendor/fatfree/lib/base.php');

if(getenv("DEBUG")) {
    $f3->set('DEBUG', getenv("DEBUG"));
}
$f3->set('ROOT', __DIR__);
$f3->set('UI', $f3->get('ROOT')."/templates/");

$f3->route('GET /', function($f3) {
        $f3->set('template', 'index.html.php');
        echo View::instance()->render('layout.html.php');
});

return $f3;
