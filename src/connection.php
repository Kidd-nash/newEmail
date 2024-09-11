<?php

$hostname = 'db_postgres_lab'; //get host name

$dbname = 'first'; // Set the database name

$username = 'pguser'; // Set the username with permissions to the database

$password = 'pgpwd'; // Set the password with permissions to the database

$dsn = "pgsql:host=$hostname;dbname=$dbname"; // Create the DSN (data source name) by combining the database type (PostgreSQL), hostname and dbname

$db = new PDO($dsn, $username, $password); //Create PDO