<?php
    function getDataFormDB() {
        // Simulate fetching data from a database
        return '<div style=ʼcolor:redʼ> div <script>alert("XSS Attack!");</script></div>';
    }
    $data = getDataFormDB();
    for($i=0; $i<10; $i++) {
        // Simulate delay
        echo "Data from DB: " . $data;
    }

    
    // Safe output using htmlspecialchars
    // $safe_data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    // echo "<br>Safe Output: " . $safe_data;
?>