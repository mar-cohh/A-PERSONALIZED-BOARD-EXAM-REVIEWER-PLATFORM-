<?php
// Include Alertify messages
if (isset($_SESSION['success']) && $_SESSION['success'] != '') {
    echo '<script>
            alertify.set("notifier", "position", "top-right");
            alertify.success("' . $_SESSION['success'] . '"); 
          </script>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error']) && $_SESSION['error'] != '') {
    echo '<script>
            alertify.set("notifier", "position", "top-right");
            alertify.error("' . $_SESSION['error'] . '"); 
          </script>';
    unset($_SESSION['error']);
}
?>