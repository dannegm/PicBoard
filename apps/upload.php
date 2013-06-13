<?php

    $name = $_FILES['images']['name'];
    move_uploaded_file($_FILES['images']['tmp_name'], $name);
    
    echo "Archivos correctamente subidos";
?>