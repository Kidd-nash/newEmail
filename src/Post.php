<?php

// include_once('./src/connection.php');

namespace Root\NewEmail;

class Post {

    protected $connection;
    
    public function __construct()
    {
        // create database connection
        $this->connection;
    }
    // public function _construct() {

    //     $this->connection {
    //             $hostname = 'db_postgres_lab'; //get host name

    //             $dbname = 'first'; // Set the database name
        
    //             $username = 'pguser'; // Set the username with permissions to the database
        
    //             $password = 'pgpwd'; // Set the password with permissions to the database
        
    //             $dsn = "pgsql:host=$hostname;dbname=$dbname"; // Create the DSN (data source name) by combining the database type (PostgreSQL), hostname and dbname
        
    //             $db = new PDO($dsn, $username, $password); //Create PDO
    // }

    // }

    public function createPost() 
    {
        return "creating";
    }

    public function listPost()
    {
        return "retrieving";
    }

    public function updatingPost() 
    {
        return "updating";
    }

    public function deletingPost() 
    {
        return "deleting";
    }

}