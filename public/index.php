<?php
$isSubmitted = ($_SERVER['REQUEST_METHOD'] === 'POST');

$isValid = true;

$firstName = '';

if ($isSubmitted) {
    $firstName = filter_input(INPUT_POST, 'firstName');

    if (strlen($firstName) < 3) {
        $isValid = false;
        $errorMessage = 'Invalid - name must contain at least 3 letters.';
    }
}

if ($isSubmitted && $isValid) {
    print "Hello, $firstName";
    die();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Tiny Sticky Post-back script</title>
    <style>
        .error {
            background: pink;
            padding: 1rem;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <?php if ($isSubmitted && !$isValid): ?>
            <div class="error"><?= $errorMessage ?></div>
        <?php endif ?>

        <input type="text" name="firstName" id="firstName" value="<?= $firstName ?>" />
        <input type="submit" value="Submit" />
    </form>
</body>

</html>