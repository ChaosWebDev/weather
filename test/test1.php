<?php

require_once __DIR__ . "/../vendor/autoload.php";

$weather = new \Chaoswd\Weather\Weather("jordangerber@gmail.com", 45.87028, -108.49190);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <pre>
        <?php
        print_r($weather);
        ?>
    </pre>
</body>

</html>