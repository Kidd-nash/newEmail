<?php

// echo "deleteId";

ob_start();

include_once('./src/connection.php');

$deleteId = $_GET["deleteId"] ?? null;

echo "Deleting Post id:" . $deleteId;

$userQuery = $db->prepare("DELETE FROM post_a_note WHERE id = :deleteId");

$userQuery->execute(['deleteId' => $deleteId]);

ob_end_clean();

header("Location: http://email.api:8080/home");
die();
?>