<?php

$f3 = require(__DIR__.'/vendor/fatfree/lib/base.php');

if(getenv("DEBUG")) {
    $f3->set('DEBUG', getenv("DEBUG"));
}
$f3->set('ROOT', __DIR__);
$f3->set('UI', $f3->get('ROOT')."/templates/");

$port = $f3->get('PORT');
$f3->set('urlbase', $f3->get('SCHEME').'://'.$_SERVER['SERVER_NAME'].(!in_array($port,[80,443])?(':'.$port):'').$f3->get('BASE'));

require_once('config/services.config.php');
$f3->set('services', $services);

$f3->route('GET /', function($f3) {
        $service = '';
        if ($f3->exists('GET.service')) {
            $service = '?service='.$f3->get('GET.service');
        }
        $f3->reroute('/cas/login'.$service);
    });
$f3->route('GET /cas/login', function($f3) {
        $cases = $f3->get('services');
        $service = $f3->get('urlbase')."/cas/login";
        if ($f3->exists('GET.service')) {
            $service = $f3->get('GET.service');
        }
        $f3->set('template', 'login.html.php');
        $f3->set('callback', $f3->get('urlbase')."/callback/".base64_encode($service)."/%servicename%");
        if ($f3->exists('GET.auto') && $f3->get('GET.auto')) {
            $service = str_replace('%service%', str_replace('%servicename%', $f3->get('GET.auto'), $f3->get('callback')),  $cases[$f3->get('GET.auto')]['cas_service']);
            $f3->reroute($service);
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
    $cases = $f3->get('services');
    $full_ticket = $f3->get('GET.ticket');
    $pos = strpos($full_ticket, '%origin:');
    $cas_ticket = substr($full_ticket, 0, $pos);
    $cas_name = substr($full_ticket, $pos + 8);
    $internal_service = $f3->get('urlbase')."/callback/".base64_encode($service)."/".$cas_name;
    $validator_url = str_replace('%ticket%', $cas_ticket, str_replace('%service%', $internal_service, $cases[$cas_name]['cas_validator']));
    $raw_xml = file_get_contents($validator_url);
    if (!$raw_xml) {
        $f3->set('xml', 'CAS ERROR ('.$validator_url.')');
       echo View::instance()->render('error.xml.php', 'text/plain');
       return;
    }
    $raw_xml = str_replace('cas:', 'cas_', $raw_xml);
    if (!strpos($raw_xml, 'cas_authenticationSuccess')) {
        $f3->set('xml', str_replace($cas_ticket, $full_ticket, $raw_xml));
        echo View::instance()->render('error.xml.php', 'text/plain');
        return;
    }
    $xml = (object)(array) new SimpleXMLElement($raw_xml);
    if (!isset($xml->cas_authenticationSuccess)) {
        $f3->set('xml', str_replace($cas_ticket, $full_ticket, $raw_xml));
        echo View::instance()->render('error.xml.php', 'text/plain');
        return;
    }
    $user_id = $xml->cas_authenticationSuccess->cas_user;
        
    $api_url = $cases[$cas_name]['api_url'];
    $secret = $cases[$cas_name]['api_secret'];
    $epoch = time();
    $api_url = str_replace('%epoch%', $epoch, $api_url);
    $api_url = str_replace('%login%', $user_id, $api_url);
    $api_url = str_replace('%md5%', md5($secret."/".$user_id."/".$epoch), $api_url);

    $raw_api_xml = file_get_contents($api_url);
    if (!$raw_api_xml) {
        $f3->set('xml', 'Viticonnect API ERROR ('.$cases[$cas_name]['api_url'].')');
       echo View::instance()->render('error.xml.php', 'text/plain');
       return;
    }
    $raw_api_xml = str_replace('cas:', 'cas_', $raw_api_xml);
    
    $api_xml = new SimpleXMLElement($raw_api_xml);
    
    $xml->cas_authenticationSuccess = (object)(array) $xml->cas_authenticationSuccess;
    $xml->cas_authenticationSuccess->cas_attributes = (object)(array) $xml->cas_authenticationSuccess->cas_attributes;
    $xml->cas_authenticationSuccess->cas_entities = (object)(array) $api_xml;
    
    $f3->set('xml', $xml);
    echo View::instance()->render('validate.xml.php', 'text/xml');
});

$f3->route('GET /test', function($f3) {
    if (!$f3->exists('GET.ticket')) {
        $auto = '';
        if ($f3->exists('GET.auto')) {
            $auto = '&auto='.$f3->get('GET.auto');
        }
        return $f3->reroute('/cas/login?service='.$f3->get('urlbase').'/test'.$auto);
    }
    $f3->mock('GET /cas/serviceValidate?ticket='.$f3->get('GET.ticket').'&service='.$f3->get('urlbase').'/test');
});


return $f3;
