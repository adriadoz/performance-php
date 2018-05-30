<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';


?>
<html>

<head>

    <link href="css/basic.css" type="text/css" rel="stylesheet" />
    <link href="css/dropzone.css" type="text/css" rel="stylesheet" />
    <script src="js/dropzone.js"></script>

</head>

<body>
<h1>Upload page</h1>
<form action="upload.php" class="dropzone"></form>

<a href="search.php">Search images</a>
</body>

</html>