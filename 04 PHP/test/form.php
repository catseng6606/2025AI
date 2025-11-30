<?php
    $len = 5;
    // TODO: Generate a form for user input contain a text field and a submit button
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'); // safe
        $name =$_POST['name']; // unsafe
        $name = substr($name, 0, $len); // limit input length to 5 characters
        echo "Hello, " . $name . " !";
    } else {
        // limit input length to 5 characters
        echo '<form method="post" action="">
                Name: <input type="text" name="name" maxlength="'.$len.'">
                <input type="submit" value="Submit">
              </form>';
    }    
?>