<?php
// Use the Null Coalescing Operator (??) to set a default value
// if it is not provided in the URL query string.
// if $_GET["name"] exists and is not null, use its value.
// Otherwise, use an empty string as the default value.
$name = $_GET["name"] ?? "";
$year = $_GET["year"] ?? "";
$state = $_GET["state"] ?? "";
$error = "";

// Checks if all get variables are set in the URL query string using isset()
if (isset($_GET["name"]) || isset($_GET["year"]) || isset($_GET["state"])){
  // all input fields are empty
  if (empty($name) && empty($year) && empty($state)){
    $error = "At least one field must contain value.";
  }
  // if the input contains only year, and year is not an integer
  elseif (empty($name) && empty($state) && !is_numeric($year)){
    $error = "Year must be a number.";
  }
  // otherwise display the query and the search result.
  else {
    // Location Header: Redirects the browser to a different page.
    header("Location: results.php?name=$name&year=$year&state=$state");
    exit();
  }
}
?>

<!DOCTYPE html>
<!-- Home page of PM database search example. -->
<html>
<head>
  <title>Associative array search example</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="styles/wp.css">
</head>

<body>
  <h2>Australian Prime Ministers</h2>
  <h3>Query</h3>

  <form method="get" action="index.php">
  <table>
    <tr><td>Name: </td><td><input type="text" name="name"></td></tr>
    <tr><td>Year: </td><td><input type="text" name="year"></td></tr>
    <tr><td>State: </td><td><input type="text" name="state"></td></tr>
    <tr><td colspan=2><input type="submit" value="Search">
                      <input type="reset" value="Reset"></td></tr>
  </table>
  </form>
  
  <!-- If the input is invalid,
   then display the error message below the search form -->
  <h3><?php echo $error; ?></h3>

  <hr>
  <p>
    Source:
    <a href="show.php?file=index.html">index.html</a> 
  </p>
</body>
</html>
