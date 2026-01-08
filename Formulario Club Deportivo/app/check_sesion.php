<?php

function is_loged_in() {
    return isset($_SESSION['id']) && !empty($_SESSION['id']);
}

function require_login() {
    if (!is_loged_in()) {
        header('Location: login.php');
        exit();
    }
}
?>