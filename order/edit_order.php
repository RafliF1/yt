<?php
session_start();

if (!isset($_SESSION['order']) || !isset($_GET['index']) || !isset($_GET['action'])) {
    header('Location: ../order.php');
    exit();
}

$index = $_GET['index'];
$action = $_GET['action'];

if ($action == 'reduce') {
    if ($_SESSION['order'][$index]['quantity'] > 1) {
        $_SESSION['order'][$index]['quantity']--;
        $_SESSION['order'][$index]['total_price'] = $_SESSION['order'][$index]['quantity'] * $_SESSION['order'][$index]['price_per_unit'];
    } else {
        unset($_SESSION['order'][$index]);
        $_SESSION['order'] = array_values($_SESSION['order']);
    }
} elseif ($action == 'remove') {
    unset($_SESSION['order'][$index]);
    $_SESSION['order'] = array_values($_SESSION['order']);
}

header('Location: ../order.php');
exit();
?>