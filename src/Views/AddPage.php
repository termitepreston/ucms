<?php

/**
 * @return array<string, array<string, string>>
 */

function validateNewPageForm(): array
{
    $errors = [];
    $sanitizedInput = [];

    // 0. Blog title validation and sanitization.
    $blogEntryTitle = filter_input(INPUT_POST, 'blogEntryTitle', FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize username
    if ($blogEntryTitle === null || $blogEntryTitle === false) {
        $errors['blogEntryTitle'] = 'Invalid blogEntryTitle input.'; // Filter error, should not happen in a well-formed form.
    } else {
        if (strlen($blogEntryTitle) < 1) {
            $errors['blogEntryTitle'] = 'Blog title must be at least 1 characters long.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $blogEntryTitle)) {
            $errors['blogEntryTitle'] = 'blogEntryTitle can only contain alphanumeric characters and underscores.';
        }
        // No further sanitization needed as FILTER_SANITIZE_STRING already handles basic sanitization.
        // If you need stricter sanitization, consider using more specific filters or custom logic.
        $sanitizedInput['blogEntryTitle'] = $blogEntryTitle;
    }


    // 1. Blog slug Validation and Sanitization
    $blogEntrySlug = filter_input(INPUT_POST, 'blogEntrySlug', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($blogEntrySlug === null || $blogEntrySlug === false) {
        $errors['blogEntrySlug'] = 'Invalid input for Blog Slug.';
    } else {
        $blogEntrySlug = trim($blogEntrySlug);
        if (empty($blogEntrySlug)) {
            $errors['blogEntrySlug'] = 'Blog slug is required.';
        } elseif (!preg_match('/^[a-zA-Z_-]+$/', $blogEntrySlug)) {
            $errors['blogEntrySlug'] = 'Blog slug should only contain letters, digits, underscores and dashes.';
        }
        $sanitizedInput['blogEntrySlug'] = $blogEntrySlug; // Sanitized by FILTER_SANITIZE_STRING, trimmed
    }

    // 2. Last Name Validation and Sanitization
    $blogEntryMarkdown = filter_input(INPUT_POST, 'blogEntryMarkdown', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($blogEntryMarkdown === null || $blogEntryMarkdown === false) {
        $errors['blogEntryMarkdown'] = 'Blog entry cannot be empty';
    } else {
        $blogEntryMarkdown = trim($blogEntryMarkdown);
        if (empty($blogEntryMarkdown)) {
            $errors['blogEntryMarkdown'] = 'Blog entry cannot be empty.';
        }


        $sanitizedInput['blogEntryMarkdown'] = $blogEntryMarkdown; // Sanitized by FILTER_SANITIZE_STRING, trimmed
    }



    return ['errors' => $errors, 'sanitizedInput' => $sanitizedInput];
}


$isSubmitted = ($_SERVER['REQUEST_METHOD'] === 'POST');
$isValid = true;
$username = $_SESSION['user']['username'] ?? '';


if ($isSubmitted) {
    $results = validateNewPageForm();

    $isValid = empty($results['errors']);

    if ($isValid) {
        $authorId = getUserIdFromUsername($pdo, $username);



        if (addBlog($pdo, $results['sanitizedInput'], $authorId)) {


            header('Location: index.php?');
        } else {
            // Username already exists...
            $results['errors']['username'] = "User {$results['sanitizedInput']['username']} already exists.";
        }
        die();
    }
}

function getUserIdFromUsername(PDO $pdo, string $username)
{
    $stmt = $pdo->prepare(
        "SELECT id
         FROM users
         WHERE username = :username"
    );

    $stmt->bindValue(":username", $username);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        print "Could not find user by the name of {$username}";
        die();
    }

    return $row['id'];
}

function addBlog(\PDO $pdo, array $inputs, int $authorId)
{
    $title = $inputs['blogEntryTitle'];
    $slug = $inputs['blogEntrySlug'];
    $content = $inputs['blogEntryMarkdown'];

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO
             blogs (title, slug, content, author_id)
             VALUES (:title, :slug, :content, :authorId)"
        );

        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':authorId', $authorId);

        $stmt->execute();
    } catch (\PDOException $e) {
        if ($e->getCode() === '23505') {
            return false;
        } else {
            throw $e;
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog Entry</title>
    <style>
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 0.2em;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <h1>Create A New Blog Entry for <?= $username ?>.</h1>

    <form id="blogEntryForm" action="" method="post">

        <?php if ($isSubmitted && !$isValid): ?>
            <div class="error">
                <pre>
                <?php print_r($results['errors']) ?>
            </pre>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="blogEntryTitle">Blog Entry Title:</label>
            <input type="text" id="blogEntryTitle" name="blogEntryTitle" onblur="validateBlogEntryTitle()" onfocus="clearError('blogEntryTitleError')" required>
            <div id="blogEntryTitleError" class="error" style="display: none;"></div>
        </div>

        <div class="form-group">
            <label for="blogEntrySlug">Blog Entry Slug:</label>
            <input type="text" id="blogEntrySlug" name="blogEntrySlug" onblur="validateBlogEntrySlug()" onfocus="clearError('blogEntrySlugError')" required>
            <div id="blogEntrySlugError" class="error" style="display: none;"></div>
        </div>

        <div class="form-group">
            <label for="blogEntryMarkdown">Markdown Text:</label>
            <textarea id="blogEntryMarkdown" name="blogEntryMarkdown" rows="10" required></textarea>
        </div>

        <div class="form-group">
            <button type="submit">Publish</button>
        </div>
    </form>

    <script>
        const form = document.getElementById('blogEntryForm');

        function clearError(errorElementId) {
            document.getElementById(errorElementId).style.display = 'none';
        }

        function displayError(errorElementId, message) {
            const errorElement = document.getElementById(errorElementId);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }

        function validateBlogEntryTitle() {
            const titleInput = document.getElementById('blogEntryTitle');
            const title = titleInput.value.trim();
            if (!title) {
                displayError('blogEntryTitleError', 'Blog title cannot be empty.');
                return false;
            } else if (/[^a-zA-Z0-9\s\p{P}]/.test(title)) { // Allows alphanumerics, spaces and punctuation from any language (unicode property)
                displayError('blogEntryTitleError', 'Title should not contain special characters beyond basic punctuation.');
                return false;
            } else {
                clearError('blogEntryTitleError');
                return true;
            }
        }

        function validateBlogEntrySlug() {
            const slugInput = document.getElementById('blogEntrySlug');
            const slug = slugInput.value.trim();
            if (!/^[a-zA-Z0-9_-]+$/.test(slug)) {
                displayError('blogEntrySlugError', 'Slug should only contain alphanumeric characters, underscores, and dashes.');
                return false;
            } else {
                clearError('blogEntrySlugError');
                return true;
            }
        }

        form.addEventListener('submit', function(event) {
            let isTitleValid = validateBlogEntryTitle();
            let isSlugValid = validateBlogEntrySlug();

            if (!isTitleValid || !isSlugValid) {
                event.preventDefault(); // Prevent form submission if validation fails
                alert('Please correct the errors in the form.');
            }
        });
    </script>

</body>

</html>