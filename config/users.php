<?php

$usersDB = [];

$usersContents = file_get_contents(__DIR__ . '/../users.txt');
$usersRows     = explode("\n", $usersContents);

$dbFields = [];
foreach ($usersRows as $row => $userRow) {
    if (empty($userRow)) {
        continue;
    }

    $fields = explode('|', $userRow);
    if ($row === 0) {
        $dbFields = $fields;
    }

    $user = [];
    foreach ($dbFields as $k => $dbField) {
        $user[$dbField] = $fields[$k];
    }
    $usersDB[] = $user;
}

return $usersDB;
