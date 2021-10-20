<?php include "functions/init.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopgroup</title>
    <link rel="icon" href="">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="topnav" id="nav">
                    <a href="index.php" class="logo"><?php if(isset($_SESSION['email'])){ echo 'Marko';}else{echo "<img src='style/img/shop.png' alt='logo'>";}?></a>
                    <a href="index.php" class="<?php if($page == 'index'){echo 'active';}?>">Početna</a>
                    <a href="category_search.php" class="<?php if($page == 'category'){echo 'active';}?>">Kategorije</a>
                <?php if(!isset($_SESSION['email'])):?>
                    <a href="login.php" class="<?php if($page == 'login'){echo 'active';}?>">Ulogujte se</a>
                    <a href="register.php" class="<?php if($page == 'register'){echo 'active';}?>">Registrujte se</a>
                <?php else:?>
                    <a href="new_ad.php" class="<?php if($page == 'new_ad'){echo 'active';}?>">Novi oglas</a>
                    <a href="my_ad.php" class="<?php if($page == 'my_ad'){echo 'active';}?>">Moji oglasi</a>
                    <a href="profile.php" class="<?php if($page == 'profile'){echo 'active';}?>">Moj profil</a>
                    <a href="save_advertisement.php" class="<?php if($page == 'save_advertisement'){echo 'active';}?>">Pratim</a>
                    <a href="logout.php">Odjavi se</a>
                <?php endif;?>
                <a  class="icon" onclick="myFunction()">
                  <i class="fa fa-bars icon-image"></i>
                </a>
            </div>
        </nav>
    </header>
    <script src="js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>