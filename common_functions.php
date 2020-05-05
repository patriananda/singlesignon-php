<?php
// used in line 48 below
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

// create a connection to LDAP Server
function LDAPConnection() {
    $ldap_connection = ldap_connect("localhost", 10389) or die("Could not connect to LDAP server.");
    ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
    $ldaptree = "ou=users,dc=example,dc=com";
    return [$ldap_connection,$ldaptree];
}

// check if there is session available
function checkLogin() {
    return isset($_SESSION['status']) && $_SESSION['status'] == "login";
}

//used in line 65 below
function checkUserLDAP($username) {
    $ldap = LDAPConnection();
    $result = ldap_search($ldap[0],$ldap[1], "(&(l=connected)(sn=".$username."))") or die ("Error in search query: ".ldap_error($ldap[0]));
    $data = ldap_get_entries($ldap[0], $result);

    // if there is data found on the array, put it on user-list array on login page to be displayed on the user list
    if ($data['count'] < 1) {
        $_SESSION['username'] = "";
        $_SESSION['status'] = "";
    }

    return $data;
}

// check if there any device connected to LDAP Server
function checkLDAP() {
    $ldap = LDAPConnection();
    // search entries based on Localhost IP
    // DN : uid=127.0.0.1,l=devices,cn=username,ou=users
    $result = ldap_search($ldap[0],$ldap[1], "(uid=".getIP().")") or die ("Error in search query: ".ldap_error($ldap[0]));
    $data = ldap_get_entries($ldap[0], $result);
    $_SESSION['users'] = [];
    
    // put data value to $value variable and set the index
    foreach ($data as $index => $value) {
        // if endex value and data type are equals to count, skip the process
        if ($index === 'count') {
            continue;
        }
        
        // splitting DN with a coma separator
        $splittedDN = explode(',', $value['dn']);
        // take the username string from splittedDN
        $dnUser = substr($splittedDN[2], 3);
        // searh entries based on l=connected with sn=username
        $data2 = checkUserLDAP($dnUser);
        
        // if there is data found on the array, put it on user-list array on login page to be displayed on the user list
        if ($data2['count'] == 1) {
            $_SESSION['users'] []= $dnUser;
        }
    }

    // if there's no data found, destroy the session
    if (count($_SESSION['users']) <= 0) {
        session_destroy();
    }
}

function destroyLogin() {
    session_destroy();
}

function destroyLDAP($username) {
    $ldap = LDAPConnection();
    // delete entry l=connected on current username
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
    
    // create/add entry l=connected based on username the user inputed
    ldap_add($ldap[0],"l=connected,cn=".$_SESSION['username'].",".$ldap[1],$info);
}