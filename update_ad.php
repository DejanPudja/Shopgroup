<?php include('inc/header.php');?>
<?php 
    $id = $_GET['id'];
    isset_update_ad($id);
    $query = "SELECT * FROM advertisement WHERE id = $id";
    $result = query($query);
?>
<?php if ($result->num_rows > 0) :?>
    <?php while ($row = $result->fetch_assoc()):?>
        <div class="container col-lg-4 container-space">
            <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                <h2 class="text-center">Izmenite oglas</h2>
                <label for=""><small>Izaberite slike (jpg, jpeg, png)</small></label>
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
                    <label for="title">Naslov oglasa</label>
                    <input type="text" class="form-control" placeholder="Unesite naslov oglasa.." value="<?php echo $row['title']?>" name="title" required>
                </div>
                <div class="form-group">
                    <label for="price">Cena*</label>
                    <input type="number" class="form-control" placeholder="Unesite cenu proizvoda (u dinarima).." value="<?php echo $row['price']?>" name="price" required>
                </div>
                <div class="form-group">
                    <label for="used">Stanje*</label>                                
                    <select class="form-control" name="used" required >
                        <option value="" disabled selected hidden>Odaberite stanje proizvoda..</option>
                        <option <?php if($row['used'] == "Novo"){echo "selected";} ?>>Novo</option>
                        <option <?php if($row['used'] == "Koristeno"){echo "selected";} ?>>Koristeno</option>
                        <option <?php if($row['used'] == "Neispravno"){echo "selected";} ?>>Neispravno</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Tekst oglasa*</label>
                    <textarea name="description" class="form-control no-resize" rows="5" placeholder="Unesite opis oglasa.." ><?php echo $row['description']?></textarea>
                </div>
                <div class="form-group">
                    <label for="place">Mesto*</label>
                    <input type="text" class="form-control" placeholder="Unesite Vaše mesto stanovanja.." name="place" value="<?php echo $row['place']?>" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">Telefon*</label>
                    <input type="text" class="form-control" placeholder="Unesite Vaš broj telefona..(0642579381)" name="phone_number" value="<?php echo $row['phone_number']?>" required>
                </div>
                <div class="form-group">
                    <label for="category">Kategorija*</label>                                
                    <select class="form-control"  name="category" required >
                        <option disabled selected hidden>Odaberite kategoriju za proizvod..</option>
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
                <input type="submit" name="submit" class="form-control form-btn" value="Izmeni oglas">
            </form>
        </div>
    <?php endwhile;?>
<?php endif;?>
<?php include('inc/footer.php');?>
