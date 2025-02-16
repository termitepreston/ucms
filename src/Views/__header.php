<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'uCMS' ?></title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>

<body>

    <header class="Header Navbar">
        <a href="index.php" class="Header__name"><span>μCMS</span></a>
        <nav class="Header__nav">
            <ul class="Header__menuBar">
                <li>
                    <a href="index.php?dashboard" class="Header__menuItem">
                        <span class="text-truncate-end">
                            <span class="Navbar__icon">

                            </span>
                            <span class="Navbar__menuText">
                                Dashboard
                            </span>
                        </span>
                    </a>
                </li>
                <!-- <li>
                    <a href="index.php?dashboard" class="Header__menuItem">
                        <span class="text-truncate-end">
                            <span class="Navbar__icon">

                            </span>
                            <span class="Navbar__menuText">
                                Sites
                            </span>
                        </span>
                    </a>
                </li> -->
            </ul>
        </nav>
    </header>