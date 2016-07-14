<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
?>

<form action="/" method="post">
    <label for="url">Insert full address of robots.txt file (http://example.com/robots.txt):</label><br>
    <input type="text" name="url">
    <button>Check</button>
</form>
<hr/>
<pre>


<?php
include "analyse_robots.php";

$analyse = new analyse_robots();
if(!empty($_POST['url'])){
    echo 'inserted URL: <b>' . $_POST['url'] . '</b>';
$analyse->getResultFormat($_POST['url']);
} else {
    echo 'Insert URL of testing file';
}
?>