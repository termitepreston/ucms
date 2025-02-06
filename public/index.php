<?php
$dateStr = (new \DateTimeImmutable())->format('F d, Y');
$total = 2 + 2;
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
    <h1><?= "total = $total." ?></h1>

    <section>
        <h1>Form processing in php</h1>
        <p>
        <form action="process.php" method="get">
            <input type="text" id="firstName" name="firstName"><label for="firstName">First Name</label><br />
            <label>
                <input type="checkbox" name="toppings[]" value="olives">
                Olives
            </label>

            <label>
                <input type="checkbox" name="toppings[]" value="pepper">
                Pepper
            </label>

            <label>
                <!-- default value is 'on' -->
                <input type="checkbox" name="toppings[]" value="garlic">
                Garlic Salt
            </label>

            <input type="submit" value="Go!" />
        </form>
        </p>
    </section>
</body>

</html>