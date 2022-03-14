<?php

function getCASes($service)  {
    return array("Declarvins" => 'https://login.declarvins.net/cas/login?service='."http://localhost:8080/callback/".base64_encode($service)."/Declarvins");
}

$f3 = require(__DIR__.'/vendor/fatfree/lib/base.php');

if(getenv("DEBUG")) {
    $f3->set('DEBUG', getenv("DEBUG"));
}
$f3->set('ROOT', __DIR__);
$f3->set('UI', $f3->get('ROOT')."/templates/");

$f3->route('GET /', function($f3) {
        $service = '';
        if ($f3->exists('GET.service')) {
            $service = '?service='.$f3->get('GET.service');
        }
        $f->reroute('/cas/login'.$service);
    });
$f3->route('GET /cas/login', function($f3) {
        $service = "http://localhost:8080/cas/login";
        if ($f3->exists('GET.service')) {
            $service = $f3->get('GET.service');
        }
        $f3->set('template', 'login.html.php');
        $cases = getCASes($service);
        if ($f3->exists('GET.auto') && $f3->get('GET.auto')) {
            $f3->reroute($cases[$f3->get('GET.auto')]);
        }
        $f3->set('cases', $cases);
        echo View::instance()->render('layout.html.php');
});

$f3->route('GET /callback/@callback/@origin', function($f3) {
        $service = base64_decode($f3->get('PARAMS.callback'));
        $ticket = $f3->get('GET.ticket').'%origin:'.$f3->get('PARAMS.origin');
        $sep = '?';
        if (strpos('?', $service) > 0) {
            $sep = '&';
        }
        $f3->reroute($service.$sep.'ticket='.$ticket);
});

$f3->route('GET /cas/serviceValidate', function($f3) {
    if (!$f3->exists('GET.service')) {
        return ;
    }
    $service = $f3->get('GET.service');
    if (!$f3->exists('GET.ticket')) {
        return ;
    }
    $full_ticket = $f3->get('GET.ticket');
    $pos = strpos($full_ticket, '%origin:');
    $cas_ticket = substr($full_ticket, 0, $pos);
    $cas_name = substr($full_ticket, $pos + 8);
    $cases = getCASes($service);
    $service = str_replace('cas/login', 'cas/serviceValidate', $cases[$cas_name]);
    $raw_xml = file_get_contents($service.'&ticket='.$cas_ticket);
    $raw_xml = str_replace('cas:', 'cas_', $raw_xml);
    $xml = (object)(array) new SimpleXMLElement($raw_xml);
    if (!isset($xml->cas_authenticationSuccess)) {
        echo $raw_xml;
        exit;
    }
    $user_id = $xml->cas_authenticationSuccess->cas_user;
    $xml->cas_authenticationSuccess = (object)(array) $xml->cas_authenticationSuccess;
    $xml->cas_authenticationSuccess->cas_attributes = (object)(array) $xml->cas_authenticationSuccess->cas_attributes;
    $xml->cas_authenticationSuccess->cas_attributes->cas_siret = '12312312312345';
    $xml->cas_authenticationSuccess->cas_attributes->cas_cvi = '0123456789';
    $xml->cas_authenticationSuccess->cas_attributes->cas_accise = 'FR01234512345';
    $xml->cas_authenticationSuccess->cas_attributes->cas_ppm = '12345';
    
    $f3->set('xml', $xml);
    echo View::instance()->render('validate.xml.php', 'text/plain');
});

return $f3;
