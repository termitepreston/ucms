<?php

require '../vendor/autoload.php';

session_start();

use MicroCMS\Connection as Connection;

try {
    $pdo = Connection::get()->connect();
    print 'A connection to the PostgreSQL database sever has been established successfully.';

    // controller functions are defined here.
    $action = filter_input(INPUT_GET, 'action');

    switch ($action) {
        case 'welcome':
            welcomePage($pdo);
            break;
        default:
            homePage($pdo);
    }

    // controller functions:

} catch (\PDOException $e) {
    print $e->getMessage();
}

function homePage(\PDO $pdo)
{
    $stmt = $pdo->prepare(
        'SELECT user_id 
         FROM user_roles
         WHERE role_id = (SELECT id FROM roles WHERE name = :role_name)'
    );

    $stmt->bindValue(':role_name', 'admin');

    $stmt->execute();

    $row = $stmt->fetch(\PDO::FETCH_ASSOC);

    if (!$row) {
        print 'Routing to action=welcome';
        header("Location: index.php?action=welcome");
        die();
    }

    print 'Rendering login form for all users (or checking for existing sessions).';
}


function welcomePage(\PDO $pdo)
{
    require_once "../src/Views/Welcome.php";
}
