<?php

$conn = mysqli_connect('localhost:3307', 'root', '', 'jbl_pizza');

//check connection
if(!$conn){
  echo 'Connection error' . mysqli_connect_error();
}

?>
