<?php
include('session.php');
requireAccess('events', 'delete');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = "DELETE FROM events WHERE id = $id";
    executeQuery($query);
}

header('Location: events.php');
exit;
?>
