<?php
// config
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
        
        // $result = ldap_search($ldap_con,$ldaptree, "(uid=127.0.0.1)") or die ("Error in search query: ".ldap_error($ldap_con));
        $result = ldap_search($ldap_con,$ldaptree, "(l=*)") or die ("Error in search query: ".ldap_error($ldap_con));
        $data = ldap_get_entries($ldap_con, $result);
        // ldap_delete($ldap_con,"l=".$data[0]["l"][0].",cn=Tesla,".$ldaptree);
        $info["cn"] = "connected";
        $info["sn"] = "connected";
        $info["objectClass"][0] = "top";
        $info["objectClass"][1] = "person";
        $info["objectClass"][2] = "inetOrgPerson";
        $info["l"] = "connected";
        ldap_add($ldap_con,"l=connected,cn=Tesla,".$ldaptree,$info);
        // echo ($data[0]["l"][0]);
        // SHOW ALL DATA
        echo '<h1>Dump all data</h1><pre>';
        print_r($data);
        
        print_r($data['count'] > 0 ? 'percarian' : 'login'); // ternary operator
        // echo '</pre>';
    }
}