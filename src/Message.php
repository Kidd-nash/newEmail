<?php

namespace Root\NewEmail;

class Message
{
    protected $connection;

    public function __construct()
    {
        // create database connection
        $this->connection;
    }

    public function doList()
    {
        $messageList = [
            'message one',
            'message two',
        ];

        ob_start();
        include_once('./src/message-list.php');

        return ob_get_clean();
    }

    public function doCreate()
    {
        return 'todo create form page';

        // create message-form file
    }

    public function handleSubmit()
    {
        //
    }

    
}