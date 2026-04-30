<?php

function connectToDb() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "din_databas";

    $db = new mysqli($host, $username, $password, $database);

    if ($db->connect_error) {
        die("Databasanslutningen misslyckades: " . $db->connect_error);
    }

    return $db;
}