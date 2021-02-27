<html>

<head>
    <title>Connecting MySQL Server</title>
</head>

<body>
    <?php
    $dbhost = 'localhost:3306';
    $dbuser = 'c2a_user';
    $dbpass = 'c2a#@!';
    $conn = mysql_connect($dbhost, $dbuser, $dbpass);

    if (!$conn) {
        die('Could not connect: ' . mysql_error());
    }
    echo 'Connected successfully';
    mysql_close($conn);
    ?>
</body>

</html>