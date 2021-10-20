<?php $page = 'register'; include('inc/header.php');?>
<div>
    <?php display_message();?>
    <?php validate_user_registration();?>
</div>
<div class="container col-lg-4">
    <form action="#" method="POST" autocomplete="off">
    <h2 class="text-center">Registrujte se</h2>
        <div class="form-group">
            <label for="first_name">Ime:</label>
            <input type="text" class="form-control" placeholder="Unesite Vaše ime" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Prezime:</label>
            <input type="text" class="form-control" placeholder="Unesite Vaše prezime" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" class="form-control" placeholder="Unesite Vaš email.." name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Lozinka:</label>
            <input type="password" class="form-control" placeholder="Unesite lozinku.." name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Potvrdite lozinku:</label>
            <input type="password" class="form-control" placeholder="Unesite lozinku.." name="confim_password" required>
        </div>
        <input type="submit" name="submit" class="form-control form-btn" value="Registrujte se">
    </form>
</div>
<?php include('inc/footer.php');?>