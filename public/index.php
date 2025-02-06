<?php
$dateStr = (new \DateTimeImmutable())->format('F d, Y');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ucms: php overview</title>
</head>

<body>
    <h1>Today is <?= $dateStr ?></h1>
</body>

</html>