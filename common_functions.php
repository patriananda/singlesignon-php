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
    session_start();
    return isset($_SESSION['status']) && $_SESSION['status'] == "login";
}

function checkLDAP() {
    $ldap = LDAPConnection();
    $username = '';
    
    $result = ldap_search($ldap[0],$ldap[1], "(|(l=connected)(uid=".getIP()."))") or die ("Error in search query: ".ldap_error($ldap[0]));
    $data = ldap_get_entries($ldap[0], $result);
    
    if ($data['count'] <= 0) {
        return false;
    }

    foreach ($data as $index => $value) {
        if ($index == 'count') {
            continue;
        }

        $splittedDN = explode(',', $value['dn']);

        if (substr($splittedDN[0], 2) == 'connected') {
            $username = substr($splittedDN[1], 3);
            break;
        }
    }

    if (!$username) {
        session_destroy();
        return false;
    }
    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['status'] = "login";

    return true;
}

function destroyLDAP() {
    $ldap = LDAPConnection();
    session_start();
    ldap_delete($ldap[0], "l=connected,cn=".$_SESSION['username'].",".$ldap[1]);
    session_destroy();
}

function createLDAP() {
    $ldap = LDAPConnection();
    $info["cn"] = "connected";
    $info["sn"] = "connected";
    $info["objectClass"][0] = "top";
    $info["objectClass"][1] = "person";
    $info["objectClass"][2] = "inetOrgPerson";
    $info["l"] = "connected";
    
    ldap_add($ldap[0],"l=connected,cn=".$_SESSION['username'].",".$ldap[1],$info);
}