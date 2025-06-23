<?php



session_start();
session_unset();
session_destroy();
echo "<script>
    alert('Vous avez été déconnecté.');
    window.location.href = 'index.php';
    </script>";
exit();