<?php

require '../vendor/autoload.php';

use MicroCMS\Connection as Connection;

try {
    Connection::get()->connect();
    print 'A connection to the PostgreSQL database sever has been established successfully.';
} catch (\PDOException $e) {
    print $e->getMessage();
}


$isSubmitted = ($_SERVER['REQUEST_METHOD'] === 'POST');

$pCode = '';
$pPrice = '';
$errors = [];

if ($isSubmitted) {
    $pCode = filter_input(INPUT_POST, 'productCode', FILTER_CALLBACK, [
        'options' => function ($value) {
            $sanitized = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);

            $trimmed = trim($sanitized);

            return (strlen($trimmed) >= 5) ? $trimmed : false;
        }
    ]);

    if (empty($pCode)) {
        $errors[] = "Username should have at least five characters.";
    }

    $pPrice = filter_input(
        INPUT_POST,
        'productPrice',
        FILTER_VALIDATE_FLOAT,
        [
            'flags' => FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_SCIENTIFIC
        ]
    );

    if (empty($pPrice)) {
        $errors[] = "Invalid price input.";
    }

    $isValid = empty($errors);

    if ($isValid) {
        print "Showing details for product {$pCode}...";
        die();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
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
            <ul class="error">
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
        <label for="product-code">Product Code</label><input type="text" name="productCode" id="product-code" value="<?= $pCode ?>">
        <br />
        <label for="product-price">Product Price</label><input type="text" name="productPrice" id="product-price" value="<?= $pPrice ?>">
        <br />
        <input type="submit" value="Submit">
    </form>
</body>

</html>