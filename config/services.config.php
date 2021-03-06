<?php 
$services = array(
    'declarvins' => array(
        "service_humanname" => "Declarvins",
        "cas_login" => "https://login.declarvins.net/cas/login?service=%service%",
        "cas_logout" => "https://login.declarvins.net/cas/logout?service=%service%",
        "cas_validator" => "https://login.declarvins.net/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://declaration.declarvins.net/viticonnect/api/%login%/%epoch%/%md5%",
    ),
    "odgrhone" => array(
        "service_humanname" => "Syndicat des Côtes du Rhône",
        "cas_login" => "https://login.syndicat-cotesdurhone.com/cas/login?service=%service%",
        "cas_logout" => "https://login.syndicat-cotesdurhone.com/cas/logout?service=%service%",
        "cas_validator" => "https://login.syndicat-cotesdurhone.com/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://declaration.syndicat-cotesdurhone.com/viticonnect/api/%login%/%epoch%/%md5%",
    ),
    "igpvaucluse" => array(
        "service_humanname" => "Vins IGP Vaucluse",
        "cas_login" => "https://login.igp.vins.24eme.fr/cas_igp/login?instance=vaucluse&service=%service%",
        "cas_logout" => "https://login.igp.vins.24eme.fr/cas_igp/logout?instance=vaucluse&service=%service%",
        "cas_validator" => "https://login.igp.vins.24eme.fr/cas_igp/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://vaucluse.igp.vins.24eme.fr/viticonnect/api/%login%/%epoch%/%md5%",
    ),
    "odgprovence" => array(
        "service_humanname" => "Syndicat des Côtes de Provence",
        "cas_login" => "https://login.syndicat-cotesdeprovence.com/cas/login?instance=vaucluse&service=%service%",
        "cas_logout" => "https://login.syndicat-cotesdeprovence.com/cas/logout?instance=vaucluse&service=%service%",
        "cas_validator" => "https://login.syndicat-cotesdeprovence.com/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://declaration.syndicat-cotesdeprovence.com/viticonnect/api/%login%/%epoch%/%md5%",
    ),
    "sancerre" => array(
        "service_humanname" => "Vins du Centre-Loire",
        "cas_login" => "https://login.vins-centre-loire.com/cas/login?service=%service%",
        "cas_logout" => "https://login.vins-centre-loire.com/cas/logout?service=%service%",
        "cas_validator" => "https://login.vins-centre-loire.com/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://declaration.vins-centre-loire.com/viticonnect/api/%login%/%epoch%/%md5%",
    )
);


if (file_exists(__DIR__."/apisecrets.config.php")) {
    include(__DIR__."/apisecrets.config.php");
}
