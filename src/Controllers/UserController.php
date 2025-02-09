<?php

declare(strict_types=1);

namespace MicroCMS\Controllers;

ob_start();

class UserController
{
    const VIEWS = __DIR__ . '/../Views/';
    const MAX_SIZE = 5 * 1024 * 1024;
    const UPLOAD_DIR = __DIR__ . '/../uploads/';


    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function logout(): void
    {
        // Prevent session fixation.
        session_regenerate_id(true); // delete the old session file

        session_unset();
        session_destroy();

        // Clear the session cookie in the browser
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Optionally, you might want to set a success message in a session for display on the next page
        session_start(); // Start session again to set a flash message
        $_SESSION['logout_message'] = "You have been successfully logged out.";
        session_write_close(); // Close session for writing immediately

        // Redirect to the login page or homepage
        header("Location: index.php"); // Or header("Location: /"); for homepage
        exit;
    }

    public function loginInternal(string $username, string $password): void
    {
        try {

            $stmt = $this->pdo->prepare(
                "SELECT username, id, password_hash
                 FROM users
                 WHERE username = :username;"
            );

            $stmt->execute([
                ':username' => $username
            ]);

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password_hash'])) {
                    // Auth success: we found our user.
                    session_regenerate_id(true);

                    // Retrieve the roles of this user.
                    $stmt = $this->pdo->prepare(
                        "SELECT ro.name
                         FROM roles AS ro
                         INNER JOIN user_roles AS ur
                         ON ur.role_id = ro.id
                         WHERE ur.user_id = :userId;"
                    );

                    $stmt->execute([
                        ':userId' => $user['id']
                    ]);

                    $roles = [];
                    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                        $roles[] = $row['ro.name'];
                    }

                    $_SESSION['loggedIn'] = true;
                    $_SESSION['userId'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['roles'] = $roles;
                    $_SESSION['last_login'] = time();
                    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

                    session_write_close();
                }
            }
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function login()
    {
        $pageTitle = 'uCMS - login';
        require_once self::VIEWS . '__header.php';


        $isSubmitted = ($_SERVER['REQUEST_METHOD'] === 'POST');
        $isValid = true;

        if ($isSubmitted) {
            $results = $this->validateLoginForm();
            $isValid = empty($results['errors']);

            if ($isValid) {
                $username = $results['sanitizedInput']['username'];
                $password = $results['sanitizedInput']['password'];

                try {
                    $this->loginInternal($username, $password);


                    header("Location: index.php?action=home");
                    die();
                } catch (\PDOException $e) {
                    $results['errors']['internal'] = $e->getMessage();
                }
            }
        }
        require_once self::VIEWS . 'login.php';

        require_once self::VIEWS . '__header.php';
    }

    /**
     * Validates and sanitizes form input for user profile update.
     *
     * @return array<string, array<string, string>> An associative array of validation errors. Empty array if no errors.
     */
    private function validateLoginForm(): array
    {
        $errors = [];
        $sanitizedInput = [];

        // 1. Username validation and sanitization.
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES); // Sanitize username
        if ($username === null || $username === false) {
            $errors['username'] = 'Invalid username input.'; // Filter error, should not happen in a well-formed form.
        } else {

            // No further sanitization needed as FILTER_SANITIZE_STRING already handles basic sanitization.
            $sanitizedInput['username'] = $username;
        }


        // 2. Password Validation
        $password = filter_input(INPUT_POST, 'password') ?? '';

        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        } else {
            $sanitizedInput['password'] = $password;
        }

        return ['errors' => $errors, 'sanitizedInput' => $sanitizedInput];
    }

    public function setupAdmin()
    {
        $pageTitle = 'uCMS - Setup Admin';

        require_once self::VIEWS . '__header.php';

        $isSubmitted = ($_SERVER['REQUEST_METHOD'] === 'POST');
        $isValid = true;



        if ($isSubmitted) {
            $results = $this->validateRegistrationForm();

            $isValid = empty($results['errors']);

            if ($isValid) {

                if ($this->registerUser($results['sanitizedInput'])) {
                    $this->loginInternal($results['sanitizedInput']['username'], $results['sanitizedInput']['password']);
                    // Close session after writing
                    session_write_close();

                    header('Location: index.php?action=home');
                    die();
                } else {
                    // Username already exists...
                    $results['errors']['username'] = "User {$results['sanitizedInput']['username']} already exists.";
                }
            }
        }

        require_once self::VIEWS . 'setup.php';

        require_once self::VIEWS . '__footer.php';
    }




    // Private functions.
    /**
     * @throws PDOException
     */
    private function fetchAllUsers(): array
    {

        $username = $_SESSION['user']['username'];

        $stmt = $this->pdo->prepare(
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

    public function register() {}

    private function fetchAllProjects(): array
    {

        $stmt = $this->pdo->prepare(
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

    private function fetchUserFromId(int $id)
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT username
             FROM users
             WHERE id = :id"
            );

            $stmt->bindValue(":id", $id);

            $stmt->execute();

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$row) {
                throw new \Exception("User with $id not found.");
            }

            return $row;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * Validates and sanitizes form input for user profile update.
     *
     * @return array<string, array<string, string>> An associative array of validation errors. Empty array if no errors.
     */
    private function validateRegistrationForm(): array
    {
        $errors = [];
        $sanitizedInput = [];

        // 0. Username validation and sanitization.
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize username
        if ($username === null || $username === false) {
            $errors['username'] = 'Invalid username input.'; // Filter error, should not happen in a well-formed form.
        } else {
            if (strlen($username) < 3) {
                $errors['username'] = 'Username must be at least 3 characters long.';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $errors['username'] = 'Username can only contain alphanumeric characters and underscores.';
            }
            // No further sanitization needed as FILTER_SANITIZE_STRING already handles basic sanitization.
            $sanitizedInput['username'] = $username;
        }


        // 1. First Name Validation and Sanitization
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($firstName === null || $firstName === false) {
            $errors['firstName'] = 'Invalid input for First Name.';
        } else {
            $firstName = trim($firstName);
            if (empty($firstName)) {
                $errors['firstName'] = 'First Name is required.';
            } elseif (!preg_match('/^[a-zA-Z]+$/', $firstName)) {
                $errors['firstName'] = 'First Name should only contain letters.';
            }
            $sanitizedInput['firstName'] = $firstName; // Sanitized by FILTER_SANITIZE_STRING, trimmed
        }

        // 2. Last Name Validation and Sanitization
        $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($lastName === null || $lastName === false) {
            $errors['lastName'] = 'Invalid input for Last Name.';
        } else {
            $lastName = trim($lastName);
            if (empty($lastName)) {
                $errors['lastName'] = 'Last Name is required.';
            } elseif (!preg_match('/^[a-zA-Z]+$/', $lastName)) {
                $errors['lastName'] = 'Last Name should only contain letters.';
            }
            $sanitizedInput['lastName'] = $lastName; // Sanitized by FILTER_SANITIZE_STRING, trimmed
        }

        // 3. Email Validation and Sanitization
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($email === false || $email === null) { // FILTER_VALIDATE_EMAIL returns false on invalid, null if not set (which we don't expect from form)
            $errors['email'] = 'Invalid email format.';
        } else {
            $sanitizedInput['email'] = $email; // Validated and sanitized by FILTER_VALIDATE_EMAIL
        }

        // 3.5 Blog title validation and sanitization.
        $blogTitle = filter_input(INPUT_POST, 'blogTitle', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($blogTitle === null || $blogTitle === false) {
            $errors['blogTitle'] = 'Invalid input for blog title.';
        } else {
            $blogTitle = trim($blogTitle);
            if (empty($blogTitle)) {
                $errors['blogTitle'] = 'Blog title is required.';
            } elseif (!preg_match('/^[a-zA-Z_-]+$/', $blogTitle)) {
                $errors['blogTitle'] = 'Blog title should only contain letters and dashes.';
            }
            $sanitizedInput['blogTitle'] = $blogTitle; // Sanitized by FILTER_SANITIZE_STRING, trimmed
        }

        // 4. Bio Sanitization
        $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Sanitize for HTML special chars, encode < > etc.
        if ($bio === null) {
            $sanitizedInput['bio'] = ''; // Treat null as empty bio (optional field)
        } else {
            $sanitizedInput['bio'] = $bio; // Sanitized with FILTER_SANITIZE_FULL_SPECIAL_CHARS
        }

        // 5. Profile Photo Upload Validation
        $profilePhotoError = $_FILES['profilePhoto']['error'] ?? UPLOAD_ERR_NO_FILE; // Check for upload errors from $_FILES, default to NO_FILE if not present

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']; // Allowed image MIME types


        if ($profilePhotoError === UPLOAD_ERR_OK) {
            $profilePhotoTmpName = $_FILES['profilePhoto']['tmp_name'] ?? '';
            $profilePhotoFileName = $_FILES['profilePhoto']['name'] ?? '';
            $profilePhotoSz = $_FILES['profilePhoto']['size'] ?? 0;
            $profilePhotoMimeType = mime_content_type($profilePhotoTmpName); // Get MIME type based on file content (more reliable)

            if (!in_array($profilePhotoMimeType, $allowedMimeTypes, true)) { // Check if MIME type starts with 'image/'
                $errors['profilePhoto'] = 'Invalid file type. Only images are allowed.';
            } else if ($profilePhotoSz > self::MAX_SIZE) {
                $errors['profilePhoto'] = 'Above the allowed file size.';
            } else {
                // 3. Generate a Unique Filename (Security and collision prevention)
                $fileExtension = pathinfo($profilePhotoFileName, PATHINFO_EXTENSION); // Get original file extension (e.g., "jpg")
                $uniqueFilename = uniqid('profile_') . '_' . bin2hex(random_bytes(8)) . '.' . strtolower($fileExtension); // Unique filename
                $destinationPath = self::UPLOAD_DIR . $uniqueFilename;

                if (!is_dir(self::UPLOAD_DIR)) {
                    if (!mkdir(self::UPLOAD_DIR, 0777, true)) { // 0777 is generally too permissive for production - adjust permissions securely
                        $errors['profilePhoto'] = "Server error: Could not create upload directory.";
                        error_log("Error creating upload directory: " . self::UPLOAD_DIR);
                    }
                }

                // 5. Move Uploaded File to Destination (Secure move operation)
                if (is_uploaded_file($profilePhotoTmpName)) { // Double check if it's a valid upload
                    if (move_uploaded_file($profilePhotoTmpName, $destinationPath)) {
                        $sanitizedInput['profilePhoto'] = $destinationPath; // Return the full path to the saved file
                    } else {
                        $errors['profilePhoto'] = "Error saving uploaded file to server.";
                        error_log("Error moving uploaded file from " . $profilePhotoTmpName . " to " . $destinationPath);
                    }
                } else {
                    $errors['profilePhoto'] = "Possible file upload attack: Invalid upload."; // Security measure
                    error_log("Possible file upload attack detected: " . $profilePhotoFileName . ", tmp_name: " . $profilePhotoTmpName);
                }

                // Perform further validation:
                // - Image dimensions
            }
        } elseif ($profilePhotoError !== UPLOAD_ERR_NO_FILE) {
            // Handle other upload errors (UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE, etc.)
            switch ($profilePhotoError) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors['profilePhoto'] = 'File size too large. Please upload a smaller image.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors['profilePhoto'] = 'File upload was partial. Please try again.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                case UPLOAD_ERR_CANT_WRITE:
                case UPLOAD_ERR_EXTENSION:
                    $errors['profilePhoto'] = 'Error uploading file. Please contact administrator.';
                    error_log("Profile photo upload error (server-side): " . $profilePhotoError); // Log server errors
                    break;
                    // UPLOAD_ERR_NO_FILE is already handled (no error in this case)
                default:
                    $errors['profilePhoto'] = 'Error uploading file.'; // Generic error for other cases
            }
        } // UPLOAD_ERR_NO_FILE means no file was uploaded, which is okay for an optional field.

        // 6. Password Validation
        $password = filter_input(INPUT_POST, 'password') ?? '';
        $passwordConfirmation = filter_input(INPUT_POST, 'passwordConfirmation') ?? '';

        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long.';
        } else {
            $hasLowercase = preg_match('/[a-z]/', $password);
            $hasUppercase = preg_match('/[A-Z]/', $password);
            $hasPunctuation = preg_match('/[^a-zA-Z0-9\s]/', $password); // Matches any non-alphanumeric, non-whitespace

            if (!$hasLowercase) {
                $errors['password'][] = 'Password must contain at least one lowercase letter.'; // Append to array to allow multiple password errors
            }
            if (!$hasUppercase) {
                $errors['password'][] = 'Password must contain at least one uppercase letter.';
            }
            if (!$hasPunctuation) {
                $errors['password'][] = 'Password must contain at least one punctuation character (e.g., !@#$%^&*).';
            }

            if (isset($errors['password']) && is_array($errors['password'])) {
                if (count($errors['password']) > 0) {
                    // If there are individual password errors, combine them into a single error message for 'password' key
                    $errors['password'] = implode(' ', $errors['password']);
                } else {
                    unset($errors['password']); // No password errors after individual checks, remove the key if it became an empty array.
                    $sanitizedInput['password'] = $password;
                }
            }

            $sanitizedInput['password'] = $password;


            if ($password !== $passwordConfirmation) {
                $errors['passwordConfirmation'] = 'Passwords do not match.';
            }
        }

        return ['errors' => $errors, 'sanitizedInput' => $sanitizedInput];
    }

    private function registerUser(array $input): bool
    {
        $username = $input['username'];
        $firstName = $input['firstName'];
        $lastName = $input['lastName'];
        $email = $input['email'];
        $blogTitle = $input['blogTitle'];
        $bio = $input['bio'];
        $profilePhoto = $input['profilePhoto'];
        $password = $input['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO users 
                (username, first_name, last_name, email, profile_picture_path, bio, password_hash, blog_title)
            VALUES
                (:username, :firstName, :lastName, :email, :profilePhoto, :bio, :passwordHash, :blogTitle)"
            );

            $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
            $stmt->bindValue(':firstName', $firstName, \PDO::PARAM_STR);
            $stmt->bindValue(':lastName', $lastName, \PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
            $stmt->bindValue(':bio', $bio, \PDO::PARAM_STR);
            $stmt->bindValue(':profilePhoto', $profilePhoto, \PDO::PARAM_STR);
            $stmt->bindValue(':passwordHash', $hashedPassword, \PDO::PARAM_STR);
            $stmt->bindValue(':blogTitle', $blogTitle, \PDO::PARAM_STMT);

            $stmt->execute();

            $adminId = $this->pdo->lastInsertId();

            $stmtRBAC = $this->pdo->prepare(
                "INSERT INTO user_roles
                (user_id, role_id)
            VALUES
                (:adminId, (SELECT id FROM roles WHERE name = :adminName))"
            );

            $stmtRBAC->bindValue(':adminId', $adminId);
            $stmtRBAC->bindValue(':adminName', 'admin');

            $stmtRBAC->execute();

            return true; // Registration successful
        } catch (\PDOException $e) {
            // Handle unique constraint violation (username already exists) specifically
            if ($e->getCode() === '23505') { // PostgreSQL unique_violation SQLSTATE - Adapt for other DBs if needed
                return false; // Indicate registration failure due to existing username
            } else {
                // Re-throw other PDOExceptions for the calling code to handle generic database errors
                throw $e;
            }
        }
    }
}
