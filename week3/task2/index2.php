<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>comment sent</title>
</head>
<body>
    <h2> PHP scripts can access GET variables using </h2>
    <table>
        <!-- table header -->
        <tr><th>array</th><th>Value</th></tr>
        <tr><td>GET:</td><td>
        <?php
        // $_GET array
        echo "{$_GET['comment']}";
        ?>
        </td></tr>
        <tr><td>REQUEST:</td><td>
        <?php
        // $_REQUEST contains the contents of $_COOKIE, $_POST, and $_GET.
        echo "{$_REQUEST['comment']}";
        ?>
        </td></tr>
    </table>
    <hr>
    <?php
        // array_key_exists: Checking if the key exists before using it
        echo "<h2>array_key_exists()</h2>";
        echo "NAME: ";
        if (array_key_exists('name', $_GET))
        {
            echo "{$_GET['name']} <br>";
        } else
        {
            echo "Not existed. <br>";
        }
        echo "{$_GET['name']} <br>";

        echo "<h3>using !empty()</h3>";
        echo "NAME: ";
        $name = $_GET['name'];
        if (!empty($name)){
            echo "$name <br>";
        } else {
            echo "Not existed. <br>";
        }
    ?>
    <hr>
    <h2> Entering parameter into the URL</h2>
    <p> http://localhost/griffith-7005/week3/task2/index2.php?comment=hello </p>
    <p> => This will produce the same result as using the form. </p>
</body>
</html>