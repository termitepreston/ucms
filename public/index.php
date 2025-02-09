<?php

declare(strict_types=1);

require '../vendor/autoload.php';

// Configure session storing cookies.
$sessionConfig = [
    'use_strict_mode' => true,         // Prevent uninitialized session IDs
    'use_cookies' => true,             // Use cookies to store session ID
    'cookie_httponly' => true,         // Prevent JavaScript access
    'cookie_secure' => true,           // Only send over HTTPS
    'cookie_samesite' => 'Strict',     // Prevent CSRF
    'gc_maxlifetime' => 1800,          // 30 minutes expiration
    'sid_length' => 128,               // Strong session ID length
    'sid_bits_per_character' => 6      // More entropy
];


// Apply configuration
foreach ($sessionConfig as $key => $value) {
    ini_set("session.$key", $value);
}

// Set custom session name
session_name('SECURE_CS322_SESSION');

// Start session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


use MicroCMS\Connection as Connection;

try {
    $pdo = Connection::get()->connect();
    print 'A connection to the PostgreSQL database sever has been established successfully.';

    // controller functions are defined here.
    $action = filter_input(INPUT_GET, 'action');

    switch ($action) {
        case 'newPage':
            newPage($pdo);
            break;
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
    // First run: check for the existence of admin.

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

    // First check if there is an active session.
    if (isset($_SESSION['user'])) {
        $role = $_SESSION['user']['role'];

        switch ($role) {
            case 'admin':
                adminDashboard($pdo);
                break;

            case 'user':
                break;
        }

        die();
    } else {
        print 'Routing to login page.';
        header('Location: index.php?action=login');
    }
}


function welcomePage(\PDO $pdo)
{
    require_once "../src/Views/Welcome.php";
}


function adminDashboard(\PDO $pdo)
{
    require_once "../src/Views/AdminDashboard.php";
}

function newPage(\PDO $pdo)
{
    require_once "../src/Views/AddPage.php";
}
