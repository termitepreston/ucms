<?php

declare(strict_types=1);


/**
 * @throws PDOException
 */
function fetchAllUsers(\PDO $pdo): array
{

    $username = $_SESSION['user']['username'];

    $stmt = $pdo->prepare(
        "SELECT username, email, first_name, last_name, profile_picture_path, bio, created_at
         FROM users
         WHERE username <> :username;"
    );

    $stmt->bindValue(":username", $username);

    $stmt->execute();

    $users = [];
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $users[] = [
            'username' => $row['username'],
            'email' => $row['email'],
            'firstName' => $row['first_name'],
            'lastName' => $row['last_name'],
            'profilePhoto' => $row['profile_picture_path'],
            'bio' => $row['bio'],
            'createdAt' => $row['created_at']
        ];
    }

    return $users;
}


function fetchAllProjects(\PDO $pdo): array
{

    $stmt = $pdo->prepare(
        "SELECT id, user_id, description, domain, language, logo_path, created_at
         FROM projects;"
    );

    $stmt->execute();

    $projects = [];
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $projects[] = [
            'id' => $row['id'],
            'userId' => $row['user_id'],
            'description' => $row['description'],
            'domain' => $row['domain'],
            'language' => $row['language'],
            'bio' => $row['bio'],
            'logoPath' => $row['logo_path'],
            'createdAt' => $row['created_at']
        ];
    }

    return $projects;
}

$users = fetchAllUsers($pdo);
$projects = fetchAllProjects($pdo);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body>
    <section>
        <h2>Manage Blog Entries</h2>
        <a class="button" href="index.php?action=newPage">Add a new entry</a>
        <table>
            <thead>
                <tr>
                    <td>Title</td>
                    <td>Slug</td>
                    <td>Full Name</td>
                    <td>Created</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($project as $project): ?>
                    <tr>
                        <td><?= $project["username"] ?></td>
                        <td><?= $project["email"] ?></td>
                        <td><?= $project["firstName"] . ' ' . $user["lastName"] ?></td>
                        <td><?= $project["createdAt"] ?></td>
                        <td><button>Remove</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>

</html>