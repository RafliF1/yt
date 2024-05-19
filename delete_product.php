<?php
include 'db_connect.php';

$id = $_GET['id'];

$sql = "DELETE FROM products WHERE id=$id";
if (mysqli_query($conn, $sql)) {
    echo "Produk berhasil dihapus.";
    header("Location:index.php");
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>