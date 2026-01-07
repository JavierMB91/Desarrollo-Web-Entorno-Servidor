<?php

function is_loged_in() {
    return isset($_SESSION['usuario']);
}

function require_login() {
    if (!is_loged_in()) {
        header('Location: login.php');
        exit();
    }
}
?>