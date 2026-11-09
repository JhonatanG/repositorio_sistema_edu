<?php
session_destroy();
echo '<script>
window.location="'.SERVERURL.'login/";
</script>';