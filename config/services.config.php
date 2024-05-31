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
    "igp" => array(
        "service_humanname" => "Vins IGP",
        "cas_login" => "https://login.igp.vins.24eme.fr/cas_igp/login?instance=igp&service=%service%",
        "cas_logout" => "https://login.igp.vins.24eme.fr/cas_igp/logout?instance=igp&service=%service%",
        "cas_validator" => "https://login.igp.vins.24eme.fr/cas_igp/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://igp.vins.24eme.fr/viticonnect/api/%login%/%epoch%/%md5%",
    ),
    "odgprovence" => array(
        "service_humanname" => "Syndicat des Côtes de Provence",
        "cas_login" => "https://login.syndicat-cotesdeprovence.com/cas/login?instance=vaucluse&service=%service%",
        "cas_logout" => "https://login.syndicat-cotesdeprovence.com/cas/logout?instance=vaucluse&service=%service%",
        "cas_validator" => "https://login.syndicat-cotesdeprovence.com/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://declaration.syndicat-cotesdeprovence.com/viticonnect/api/%login%/%epoch%/%md5%",
    ),
    "sancerre" => array(
        "service_humanname" => "Vins du Centre-Loire (BIVC)",
        "cas_login" => "https://login.vins-centre-loire.com/cas/login?service=%service%",
        "cas_logout" => "https://login.vins-centre-loire.com/cas/logout?service=%service%",
        "cas_validator" => "https://login.vins-centre-loire.com/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://declaration.vins-centre-loire.com/viticonnect/api/%login%/%epoch%/%md5%",
    ),
    "odgcentreloire" => array(
        "service_humanname" => "ODG du Centre-Loire",
        "cas_login" => "https://login.aoc-centre-loire.fr/cas/login?service=%service%",
        "cas_logout" => "https://login.aoc-centre-loire.fr/cas/logout?service=%service%",
        "cas_validator" => "https://login.aoc-centre-loire.fr/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://login.aoc-centre-loire.fr/viticonnect/api/%login%/%epoch%/%md5%",
    ),
    "civa" => array(
        "service_humanname" => "Vins d'Alsace",
        "cas_login" => "https://login.vinsalsace.pro/cas/login?service=%service%",
        "cas_logout" => "https://login.vinsalsace.pro/cas/logout?service=%service%",
        "cas_validator" => "https://login.vinsalsace.pro/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://declaration.vinsalsace.pro/drm/viticonnect/api/%login%/%epoch%/%md5%?extra=%extra%"
    ),
    "institutrhodanien" => array(
        "service_humanname" => "Institut Rhodanien",
        "cas_login" => "https://login.institut-rhodanien.com/cas/login?service=%service%",
        "cas_logout" => "https://login.institut-rhodanien.com/cas/logout?service=%service%",
        "cas_validator" => "https://login.institut-rhodanien.com/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://login.institut-rhodanien.com/viticonnect/api.php?login=%login%&epoch=%epoch%&md5=%md5%"
    ),
    "test" => array(
        "service_humanname" => "Test",
        "cas_login" => "https://test.24eme.fr/cas/login?service=%service%",
        "cas_logout" => "https://test.24eme.fr/cas/logout?service=%service%",
        "cas_validator" => "https://test.24eme.fr/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://test.24eme.fr/viticonnect/api.php?login=%login%&epoch=%epoch%&md5=%md5%",
        "hidden" => true
    ),
    "ivso" => array(
        "service_humanname" => "IVSO",
        "cas_login" => "https://login.ivsopro.com/cas/login?service=%service%",
        "cas_logout" => "https://login.ivsopro.com/cas/logout?service=%service%",
        "cas_validator" => "https://login.ivsopro.com/cas/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://declaration.ivsopro.com/viticonnect/api/%login%/%epoch%/%md5%?extra=%extra%"
    ),
    "ivbd" => array(
        "service_humanname" => "IVBD",
        "cas_login" => "https://login.ivbdpro.fr/cas_ivbd/login?service=%service%",
        "cas_logout" => "https://login.ivbdpro.fr/cas_ivbd/logout?service=%service%",
        "cas_validator" => "https://login.ivbdpro.fr/cas_ivbd/serviceValidate?service=%service%&ticket=%ticket%",
        "api_url" => "https://declaration.ivbdpro.fr/viticonnect/api/%login%/%epoch%/%md5%?extra=%extra%"
    )

);


if (file_exists(__DIR__."/apisecrets.config.php")) {
    include(__DIR__."/apisecrets.config.php");
}
