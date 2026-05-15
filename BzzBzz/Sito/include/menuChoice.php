<?php

$json = file_get_contents(__DIR__ . '/pages.json');


$pageName = basename($_SERVER['PHP_SELF']);

$obj = json_decode($json);



// controlla che pagename sia dentro loggedInPages
if(in_array($pageName, $obj->loggedInPages)){
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['IDWasper'])) {
        header("Location: ../Pagine/loginForm.php");
        exit();
    }
    include __DIR__ . '/../header_footer/HeaderUser.php';
}

if(in_array($pageName, $obj->DBPages)){
    require_once(__DIR__ . '/DBHandler.php');
}

if(in_array($pageName, $obj->adminpages)){
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header("Location: ../Pagine/hPage.php");
        exit();
    }
    include __DIR__ . '/../header_footer/headerAdmin.php';
}elseif(in_array($pageName, $obj->userpages)){
    include __DIR__ . '/../header_footer/HeaderUser.php';
}