<?php
include('session.php');
requireAccess('content', 'delete');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = "DELETE FROM content WHERE id = $id";
    executeQuery($query);
}

header('Location: content.php');
exit;
?>
