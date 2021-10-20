<?php include('inc/header.php');?>
<?php 
    $category = $_GET['id'];
    $query = "SELECT * FROM advertisement WHERE category = '$category'";
    $result = query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<h3 class='text-center header-text'>".$row['category']."</h3> ";
        break;
        }
    }
    category_search($category);
?>
<?php include('inc/footer.php');?>
