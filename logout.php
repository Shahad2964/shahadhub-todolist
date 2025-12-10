<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>




// SECURE CODE: The task is now safe for display. Fulfills SR1.
<span class="<?php echo $t['completed'] ? 'completed' : ''; ?>">
    <?php echo htmlspecialchars($t['task']); ?> 
</span>