<?php

session_start();
include('conf/conf.php'); // or $_SESSION['licencia'] !==LICENCIA
  if (empty($_SESSION['usuario_cuentas'])) { header('location: login.php'); exit();  }
    else {  header('location: dashboard.php');  }
