<?php
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header( "Location: ./index.php");
    exit();
}

session_start();
include('common_functions.php');

if ($_POST["action"] == "logout") {
    destroyLDAP($_SESSION['username']);
    destroyLogin();
    header("location:./login.php");
}

if ($_POST["action"] == "logoutLDAP") {
    destroyLDAP($_POST["username"]);
    header("location:./login.php");
}

if ($_POST["action"] == "login") {
    $_SESSION['username'] = $_POST["username"];
    $_SESSION['status'] = "login";
    header( "Location: ./index.php");
}

if ($_POST["action"] == "loginLDAP") {
    $ldap_dn = "cn=".$_POST["username"].",ou=users,dc=example,dc=com";
    $ldap_password = $_POST["password"];
    $ldap = LDAPConnection();
    if (ldap_bind($ldap[0], $ldap_dn, $ldap_password)) {
        $_SESSION['username'] = $_POST["username"];
        $_SESSION['status'] = "login";
        createLDAP();
        // echo ($_SESSION['username']);
        // die();
        header( "Location: ./index.php");
        exit();
    } else {
        // masih error di sini
        $_SESSION['loginerror'] = "Invalid username or password";
        header( "Location: ./login.php");
    }
}