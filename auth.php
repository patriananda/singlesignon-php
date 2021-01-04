<?php
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header( "Location: ./index.php");
    exit();
}

session_start();
include('common_functions.php');

// used in index.php line 27
if ($_POST["action"] == "logout") {
    destroyLDAP($_SESSION['username']);
    destroyLogin();
    header("location:./login.php");
}

// used in login.php line 43
if ($_POST["action"] == "logoutLDAP") {
    destroyLDAP($_POST["username"]);
    header("location:./login.php");
}

// used in login.php line 36
if ($_POST["action"] == "login") {
    $_SESSION['username'] = $_POST["username"];
    $_SESSION['status'] = "login";
    header( "Location: ./index.php");
}

// used in login.php line 90
if ($_POST["action"] == "loginLDAP") {
    $ldap_dn = "cn=".$_POST["username"].",ou=users,dc=example,dc=com";
    $ldap_password = $_POST["password"];
    $ldap = LDAPConnection();
    
    // jika proses binding berhasil
    if (ldap_bind($ldap[0], $ldap_dn, $ldap_password)) {
        $_SESSION['username'] = $_POST["username"];
        $_SESSION['status'] = "login";
        createLDAP();
        header( "Location: ./index.php");
        exit();
    } else {
        $_SESSION['loginerror'] = "Invalid username or password";
        header( "Location: ./login.php");
    }
}

if ($_POST["action"] == "registerLDAP") {
    $_SESSION['username'] = $_POST["username"];
    $_SESSION['password'] = $_POST["password"];

    if (!$_POST["username"]) {
        $_SESSION['registererror'] = "Field username must not be empty";
    } else if (!$_POST["password"]) {
        $_SESSION['registererror'] = "Field password must not be empty";
    } else if ($_POST["password"] != $_POST["password2"]) {
        $_SESSION['registererror'] = "Field password must match";
    } else {
        $ldap = LDAPConnection();
        registerUserLDAP();
        header( "Location: ./login.php");
        return;
    }

    header( "Location: ./register.php");
}