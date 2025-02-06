<?php

$firstName = filter_input(INPUT_POST, 'firstName');

var_dump($firstName);

$toppings = filter_input(INPUT_GET, 'toppings', options: FILTER_REQUIRE_ARRAY);



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>

<body>
    <h1>Welcome <?= $firstName ?></h1>

    <p>
        <?= empty($toppings) ? 'No toppings selected' : implode(' + ', $toppings) ?>
    </p>
</body>

</html>