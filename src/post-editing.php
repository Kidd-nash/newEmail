<?php

include_once('./src/connection.php');

echo 'editing page';

$editingId = $_GET["editingId"] ?? null;

echo "Editing Post id:" . $editingId;