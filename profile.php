<?php $page = 'profile'; include('inc/header.php');?>
<?php 
    $user = get_user();
    if(isset($_POST['submit'])){
        $password = $_POST['password'];
        $new_password = $_POST['new_password'];
        update_password($password,$new_password);
    }
?>
    <div class="container col-lg-4 container-space">
        <form action="#" method="POST" autocomplete="off">
        <h2 class="text-center">Moj profil</h2>
            <div class="form-group">
                <label for="first_name">Ime</label>
                <input type="text" class="form-control" value="<?php echo $user['first_name']?>" name="first_name" disabled>
            </div>
            <div class="form-group">
                <label for="last_name">Prezime</label>
                <input type="text" class="form-control" value="<?php echo $user['last_name']?>" name="last_name" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" value="<?php echo $user['email']?>" name="email" disabled>
            </div>
            <div class="form-group">
                <label for="password">Lozinka</label>
                <input type="password" class="form-control" placeholder="Unesite Vašu lozinku.." name="password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Nova lozinka</label>
                <input type="password" class="form-control" placeholder="Unesite novu lozinku.." name="new_password" >
            </div>
            <input type="submit" name="submit" class="form-control form-btn" value="Potvrdi promene">
        </form>
    </div>
<?php include('inc/footer.php');?>
