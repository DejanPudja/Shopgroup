<?php include('inc/header.php');
    $id = $_GET['id'];
    delete_ad($id); 
    redirect('index.php');    
?>