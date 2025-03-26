<?php 
// ...existing HTML code...

if (isset($_REQUEST['erreurs'])) {
    foreach ($_REQUEST['erreurs'] as $erreur) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($erreur) . '</div>';
    }
}
?>