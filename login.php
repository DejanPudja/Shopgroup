<?php $page = 'login'; include('inc/header.php');?>
<?php display_message();?>
<?php validate_user_login();?>
     
<div class="container col-lg-4">
    <form action="#" method="POST" autocomplete="off">
    <h2 class="text-center">Ulogujte se</h2>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" class="form-control" placeholder="Unesite Vaš email.." name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Lozinka:</label>
            <input type="password" class="form-control" placeholder="Unesite Vašu lozinku.." name="password" required>
        </div>
        <input type="submit" name="submit" class="form-control form-btn" value="Ulogujte se">
    </form>
</div>
<?php include('inc/footer.php');?>