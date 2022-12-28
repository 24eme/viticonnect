<?php

$f3 = require(__DIR__.'/vendor/fatfree/lib/base.php');


class VitiConnect {
    
    public function home(Base $f3) {
        $service = '';
        if ($f3->exists('GET.service')) {
            $service = '?service='.$f3->get('GET.service');
        }
        $f3->reroute('/cas/login'.$service);
    }
    
    public function cas_login_get(Base $f3) {
        $cases = $f3->get('services');
        if ($f3->exists('GET.service')) {
            $service = $f3->get('GET.service');
        }
        $f3->set('limits', null);
        $limit = null;
        if ($f3->exists('GET.limit')) {
            $limit = $f3->get('GET.limit');
        }
        if ($f3->exists('PARAMS.limit')) {
            $limit = $f3->get('PARAMS.limit');
        }
        if ($limit) {
            $limits = array();
            foreach(explode(',', $limit) as $k) {
                $limits[$k] = $k;
            }
            $f3->set('limits', $limits);
        }
        if ($f3->exists("SESSION.origin")) {
            $f3->set('origin', $f3->get("SESSION.origin"));
            $f3->set('originname', $cases[$f3->get('origin')]['service_humanname']);
            if (!isset($service) || !$service) {
                $f3->set('template', 'logged.html.php');
                echo View::instance()->render('layout.html.php');
                return;
            }
            $f3->set('GET.auto', $f3->get('origin'));
        }
        if (isset($service) && $service && $limit && count($limits) == 1) {
            return $this->redirect2realcas($f3, $limit, $service);
        }
        if (!isset($service) || !$service){
            $service = $f3->get('urlbase')."/cas/login";
        }
        $f3->set('service', $service);
        $f3->set('servicename', preg_replace('/https?:..(www\.)?([^\/]*)\/.*/', '\2', $service));
        $f3->set('callback', $f3->get('urlbase')."/callback/".base64_encode($service)."/%servicename%");
        if ($f3->exists('GET.auto') && $f3->get('GET.auto')) {
            $service = str_replace('%service%', str_replace('%servicename%', $f3->get('GET.auto'), $f3->get('callback')),  $cases[$f3->get('GET.auto')]['cas_login']);
            return $f3->reroute($service);
        }
        $f3->set('cases', $cases);
        $f3->set('template', 'login.html.php');
        echo View::instance()->render('layout.html.php');
    }
    
    public function cas_login_post(Base $f3) {
        $service = $f3->get('POST.service');
        $key = $f3->get('POST.cas_choice');
        return $this->redirect2realcas($f3, $key, $service);
    }
    
    private function redirect2realcas(Base $f3, $caskey, $service) {
        $cases = $f3->get('services');
        $callback = $f3->get('urlbase')."/callback/".base64_encode($service)."/%servicename%";
        $url = str_replace('%service%', str_replace('%servicename%', $caskey, $callback), $cases[$caskey]['cas_login']);
        return $f3->reroute($url);
    }
    
    public function callback(Base $f3) {
        $service = base64_decode($f3->get('PARAMS.callback'));
        $ticket = $f3->get('GET.ticket').'%origin:'.$f3->get('PARAMS.origin').'-viticonnect';
        $f3->set('SESSION.origin', $f3->get('PARAMS.origin'));
        $f3->set('SESSION.ticket', $f3->get('GET.ticket'));
        $sep = '?';
        if (strpos($service, '?') > 0) {
            $sep = '&';
        }
        $f3->reroute($service.$sep.'ticket='.urlencode($ticket));
    }
    
    public function cas_logout(Base $f3) {
        $cases = $f3->get('services');
        $key = $f3->get('SESSION.origin');
        $f3->clear('SESSION.origin');
        $f3->clear('SESSION.ticket');

        $service = ($f3->get('GET.service')) ? $f3->get('GET.service') : $f3->get('urlbase').'/cas/login';

        if ($key) {
            return $f3->reroute(str_replace('%service%', $service, $cases[$key]['cas_logout']));
        }

        return $f3->reroute($service);
    }
    
    public function cas_servicevalidate(Base $f3) {
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
        $cas_name = str_replace('-viticonnect', '', substr($full_ticket, $pos + 8));
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
        libxml_use_internal_errors(true);
        $xml = (object) (array) simplexml_load_string($raw_xml);
        if (libxml_get_errors()){
            $f3->set('xml', "Error handling xml : ".$raw_xml);
            echo View::instance()->render('error.xml.php', 'text/plain');
            return;
        }
        if (!isset($xml->cas_authenticationSuccess)) {
            $f3->set('xml', str_replace($cas_ticket, $full_ticket, $raw_xml));
            echo View::instance()->render('error.xml.php', 'text/plain');
            return;
        }
        $user_id = $xml->cas_authenticationSuccess->cas_user;

        $api_url = $cases[$cas_name]['api_url'];
        $secret = $cases[$cas_name]['api_secret'];
        $isExtra = isset($cases[$cas_name]['api_extra']) && in_array(parse_url($service)['host'], $cases[$cas_name]['api_extra']);
        $epoch = time();
        $api_url = str_replace('%epoch%', $epoch, $api_url);
        $api_url = str_replace('%login%', $user_id, $api_url);
        $api_url = str_replace('%extra%', (int) $isExtra, $api_url);
        $api_url = str_replace('%md5%', md5($secret."/".$user_id."/".$epoch), $api_url);
        $raw_api_xml = file_get_contents($api_url);
        if (!$raw_api_xml) {
            $f3->set('xml', 'Viticonnect API ERROR ('.$cases[$cas_name]['api_url'].')');
            echo View::instance()->render('error.xml.php', 'text/plain');
            return;
        }
        $raw_api_xml = str_replace('cas:', 'cas_', $raw_api_xml);
        
        libxml_use_internal_errors(true);
        $api_xml = (object) (array) simplexml_load_string($raw_api_xml);
        if (libxml_get_errors()){
            $f3->set('xml', "Error handling xml : ".$raw_api_xml);
            echo View::instance()->render('error.xml.php', 'text/plain');
            return;
        }
        
        $xml->cas_authenticationSuccess = (object)(array) $xml->cas_authenticationSuccess;
        $xml->cas_authenticationSuccess->cas_attributes = (object)(array) $xml->cas_authenticationSuccess->cas_attributes;
        if (isset($api_xml->cas_viticonnect_entities_number)) {
            foreach( (array) $api_xml as $k => $v) {
                $xml->cas_authenticationSuccess->cas_attributes->{$k} = $v;
            }
        }else{
            $xml->cas_authenticationSuccess->cas_attributes->cas_entities_number = 0;
        }
        $f3->set('xml', $xml);
        echo View::instance()->render('validate.xml.php', 'text/xml');
    }
    
    public function test(Base $f3) {
        if (!$f3->exists('GET.ticket')) {
            $auto = '';
            if ($f3->exists('GET.auto')) {
                $auto = '&auto='.$f3->get('GET.auto');
            }
            return $f3->reroute('/cas/login?service='.$f3->get('urlbase').'/test'.$auto);
        }
        $f3->mock('GET /cas/serviceValidate?ticket='.$f3->get('GET.ticket').'&service='.$f3->get('urlbase').'/test');
    }
    
}

if(getenv("DEBUG")) {
    $f3->set('DEBUG', getenv("DEBUG"));
}
$f3->set('ROOT', __DIR__);
$f3->set('UI', $f3->get('ROOT')."/templates/");

$port = $f3->get('PORT');
$f3->set('urlbase', $f3->get('SCHEME').'://'.$_SERVER['SERVER_NAME'].(!in_array($port,[80,443])?(':'.$port):'').$f3->get('BASE'));

require_once('config/services.config.php');
$f3->set('services', $services);
$f3->route('GET /', 'VitiConnect->home');

$f3->route('GET /cas/login', 'VitiConnect->cas_login_get');
$f3->route('POST /cas/login', 'VitiConnect->cas_login_post');
$f3->route('GET /cas/logout',  'VitiConnect->cas_logout');

$f3->route('GET /cas/@limit/login', 'VitiConnect->cas_login_get');
$f3->route('POST /cas/@limit/login', 'VitiConnect->cas_login_post');
$f3->route('GET /cas/@limit/logout',  'VitiConnect->cas_logout');

$f3->route('GET /callback/@callback/@origin', 'VitiConnect->callback');

$f3->route('GET /cas/serviceValidate', 'VitiConnect->cas_servicevalidate');
$f3->route('GET /cas/proxyValidate', 'VitiConnect->cas_servicevalidate');
$f3->route('GET /cas/@limit/serviceValidate', 'VitiConnect->cas_servicevalidate');
$f3->route('GET /cas/@limit/proxyValidate', 'VitiConnect->cas_servicevalidate');

$f3->route('GET /test', 'VitiConnect->test');

return $f3;
