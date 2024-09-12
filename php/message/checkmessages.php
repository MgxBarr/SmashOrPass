<?php
session_start();
echo isset($_SESSION['arecumessage']) ? $_SESSION['arecumessage'] : 0;
?>