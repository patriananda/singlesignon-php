<?php
function getIP() {
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip === '::1' ? '127.0.0.1' : $ip;
}

function LDAPConnection() {
    $ldap_connection = ldap_connect("localhost", 10389) or die("Could not connect to LDAP server.");
    ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
    $ldaptree = "ou=users,dc=example,dc=com";
    return [$ldap_connection,$ldaptree];
}

function checkLogin() {
    return isset($_SESSION['status']) && $_SESSION['status'] == "login";
}

function checkLDAP() {
    $ldap = LDAPConnection();
    $result = ldap_search($ldap[0],$ldap[1], "(uid=".getIP().")") or die ("Error in search query: ".ldap_error($ldap[0]));
    $data = ldap_get_entries($ldap[0], $result);
    
    if ($data['count'] <= 0) {
        return false;
    }
    
    $_SESSION['users'] = [];
    
    foreach ($data as $index => $value) {
        if ($index === 'count') {
            continue;
        }
        
        $splittedDN = explode(',', $value['dn']);
        $dnUser = substr($splittedDN[2], 3);
        $result2 = ldap_search($ldap[0],$ldap[1], "(&(l=connected)(sn=".$dnUser."))") or die ("Error in search query: ".ldap_error($ldap[0]));
        $data2 = ldap_get_entries($ldap[0], $result2);
        
        if ($data2['count'] == 1) {
            $_SESSION['users'] []= $dnUser;
        }
    }

    if (count($_SESSION['users']) <= 0) {
        session_destroy();
        return false;
    }

    return true;
}

function destroyLogin() {
    session_destroy();
}

function destroyLDAP($username) {
    $ldap = LDAPConnection();
    ldap_delete($ldap[0], "l=connected,cn=".$username.",".$ldap[1]);
}

function createLDAP() {
    $ldap = LDAPConnection();
    $info["objectClass"][0] = "top";
    $info["objectClass"][1] = "person";
    $info["objectClass"][2] = "inetOrgPerson";
    $info["cn"] = "connected";
    $info["sn"] = $_SESSION['username'];
    $info["l"] = "connected";
    
    ldap_add($ldap[0],"l=connected,cn=".$_SESSION['username'].",".$ldap[1],$info);
}