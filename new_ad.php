<?php $page = 'new_ad'; include('inc/header.php')?>

<?php 
    $data = get_user();
    validate_create_ad();
?>

<div class="container col-lg-4 container-space">
    <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
    <h2 class="text-center">Šta oglašavate</h2>
        <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo $data['id'] ?>" name="user_id">
        </div>
        <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo $data['email'] ?>" name="email">
        </div>
        <label for=""><small><i>Izaberite slike (jpg i jpeg)</i></small></label>
        <div class="form-group text-center" >
            <input type="file" name="image1" required>
        </div>
        <div class="form-group text-center">
            <input type="file" name="image2" required>
        </div>
        <div class="form-group text-center">
            <input type="file" name="image3">
        </div>
        <div class="form-group text-center">
            <input type="file" name="image4">
        </div>
        <div class="form-group text-center">
            <input type="file" name="image5">
        </div>
        <div class="form-group">
            <label for="title">Naslov oglasa*</label>
            <input type="text" class="form-control" placeholder="Unesite naslov oglasa.." name="title" required>
        </div>
        <div class="form-group">
            <label for="price">Cena*</label>
            <input type="number" class="form-control" placeholder="Unesite cenu proizvoda (u dinarima).." name="price" required>
        </div>
        <div class="form-group">
            <label for="used">Stanje*</label>                                
            <select class="form-control"  name="used" required >
                <option value="" disabled selected hidden>Odaberite stanje proizvoda..</option>
                <option>Novo</option>
                <option>Koristeno</option>
                <option>Neispravno</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Tekst oglasa*</label>
            <textarea name="description" class="form-control no-resize" rows="5" placeholder="Unesite opis oglasa.."></textarea>
        </div>
        <div class="form-group">
            <label for="place">Mesto*</label>
            <input type="text" class="form-control" placeholder="Unesite Vaše mesto stanovanja.." name="place" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Telefon*</label>
            <input type="text" class="form-control" placeholder="Unesite Vaš broj telefona..(0642579381)" name="phone_number" required>
        </div>
        <div class="form-group">
            <label for="category">Kategorija*</label>                                
            <select class="form-control"  name="category" required >
                <option value="" disabled selected hidden>Odaberite kategoriju za proizvod..</option>
                <option>Alati i orudja</option>
                <option>Audio</option>
                <option>Automobili</option>
                <option>Automobili/oprema</option>
                <option>Bela tehnika</option>
                <option>Bicikli</option>
                <option>Bicikli/oprema</option>
                <option>Dvoriste i basta</option>
                <option>Elektornika i komponente</option>
                <option>Igracke</option>
                <option>Knjige</option>
                <option>Kompijuteri</option>
                <option>Kućni ljubimci</option>
                <option>Hrana za ljubimce</option>
                <option>Kupatilo i oprema</option>
                <option>Mobilni telefoni</option>
                <option>Motocikli</option>
                <option>Motocikli/oprema</option>
                <option>Nakit/satovi/dragocenosti</option>
                <option>Namestaj</option>
                <option>Obuca</option>
                <option>Odeca</option>
                <option>Sport i raznoda</option>
                <option>Skolski pribor</option>
                <option>TV/Video</option>
            </select>
        </div>
        <div class="form-group">
            <p><small><i>Napomena: oglas se briše nakon 30 dana.</i></small></p>
        </div>
        <input type="submit" name="submit" class="form-control form-btn" value="Postavi oglas">
    </form>
</div>
<?php include('inc/footer.php');?>
