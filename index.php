<?php $page = 'index'; include('inc/header.php')?>
<?php 
    get_user();
    fetch_all_ad();
?>
<?php include('inc/footer.php');?>