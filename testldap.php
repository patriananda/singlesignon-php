<?php
// config
session_start();
// $ldapserver = 'localhost';
$ldap_con = ldap_connect("localhost", 10389) or die("Could not connect to LDAP server.");
ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
$ldapuser = 'uid=admin,ou=system';
$ldappass = 'secret';
$ldaptree = "ou=users,dc=example,dc=com";

// connect
// $ldap_con = ldap_con($ldap_con) or die("Could not connect to LDAP server.");

if($ldap_con) {
    // binding to ldap server
    $ldapbind = ldap_bind($ldap_con, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldap_con));
    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful...<br /><br />";
        
        $result = ldap_search($ldap_con,$ldaptree, "(l=*)") or die ("Error in search query: ".ldap_error($ldap_con));
        // $result = ldap_search($ldap_con,$ldaptree, "(uid=127.0.0.1)") or die ("Error in search query: ".ldap_error($ldap[0]));
        // $result = ldap_search($ldap_con,$ldaptree, "(&(l=connected)(sn=".$username."))") or die ("Error in search query: ".ldap_error($ldap[0]));
        // $result = ldap_search($ldap_con,$ldaptree, "(&(l=connected)(sn=tesla))") or die ("Error in search query: ".ldap_error($ldap[0]));
        $data = ldap_get_entries($ldap_con, $result);
        // $result = ldap_search($ldap_con,$ldaptree, "(cn=*)") or die ("Error in search query: ".ldap_error($ldap_con));
        //* create entry l=connected berdasarkan username tesla
        /*
        $username = "tesla";
        $info["cn"] = "connected";
        $info["sn"] = $username;
        $info["objectClass"][0] = "top";
        $info["objectClass"][1] = "person";
        $info["objectClass"][2] = "inetOrgPerson";
        $info["l"] = "connected";
        ldap_add($ldap_con,"l=connected,cn={$username},{$ldaptree}", $info);
        */
        $username = "khotim";
        $info["cn"] = $username;
        $info["sn"] = $username;
        $info["objectClass"][0] = "top";
        $info["objectClass"][1] = "person";
        $info["objectClass"][2] = "organizationalPerson";
        $info["objectClass"][3] = "inetOrgPerson";
        $info["userPassword"] = "abdul";
        // $info['userPassword'] = '{MD5}'.base64_encode(pack('H*',md5("bell")));
        ldap_add($ldap_con,"cn={$username},{$ldaptree}", $info);
        
        // ldap_delete($ldap_con,"l=connected,cn=".$username.",".$ldaptree);

        // ldap_mod_del($ldap_con, $ldaptree, $data[0]["userpassword"][0]);
        // echo ($data[0]["userpassword"][0]);
        // SHOW ALL DATA
        echo '<h1>Dump all data</h1><pre>';
        print_r($data);

        // print_r($_SESSION['users']);
        
        print_r($data['count'] > 0 ? 'percarian' : 'login'); // ternary operator
        // echo '</pre>';
    }
}