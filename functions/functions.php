<?php
function clean($str){
    return htmlentities($str);
}

function redirect($location){
    header("location: {$location}");
    exit();
}

function set_message($message){
    if(!empty($message)){
        $_SESSION['message'] = $message;
    }else{
        $message = "";
    }
}
//PORUKA 
function display_message(){
    if(isset( $_SESSION['message'])){
        echo  "<div class='message'>". $_SESSION['message']."</div>"; 
        unset($_SESSION['message']);
    }
}
//PROVERA DA LI POSTOJI EMAIL U BAZI
function email_exists($email){
    $email  = filter_var($email,FILTER_SANITIZE_EMAIL);
    $query  = "SELECT * FROM users WHERE email = '$email'";
    $result = query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $mail  = $row['email'];
            if($email == $mail){
                return true;
            }
        }
    }
    return false;
}
//VALIDACIJA ZA REGISTRACIJU
function validate_user_registration(){
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $first_name = clean($_POST['first_name']);
        $last_name  = clean($_POST['last_name']);
        $email      = clean($_POST['email']);
        $password   = clean($_POST['password']);
        $confim_password = clean($_POST['confim_password']);

        if(strlen($first_name) < 3){
            $errors[] = "Vaše Ime ne moze da sadrži manje od 3 karaktera!"; 
        }
        if(strlen($last_name) < 3){
            $errors[] = "Vaše Prezime ne može da sadrži manje od 3 karaktera!"; 
        }
        if(email_exists($email)){
            $errors[] = "Vaš Email je zauzet!"; 
        }
        if(strlen($password) < 8){
            $errors[] = "Vaša lozinka ne može da sadrži manje od 8 karaktera!"; 
        }
        if($password != $confim_password){
            $errors[] = "Lozinka nije ispravno potvrđena!";
        }
        if(!empty($errors)){
            foreach($errors as $error){
                echo '<div class="alert"><i class="fa fa-exclamation-triangle">'. $error .'</i></div>';
            }
        }
        else{
            $first_name = filter_var($first_name,FILTER_SANITIZE_STRING);
            $last_name  = filter_var($last_name, FILTER_SANITIZE_STRING);
            $email      = filter_var($email,     FILTER_SANITIZE_EMAIL);
            $password   = filter_var($password,  FILTER_SANITIZE_STRING);
            create_user($first_name,$last_name,$email,$password);
        }
    }
}
//KREIRANJE KORISNIKA
function create_user($first_name,$last_name,$email,$password){
    $first_name = ucwords(escape($first_name));
    $last_name  = ucwords(escape($last_name));
    $email      = escape($email);
    $password   = escape($password);
    $password   = password_hash($password,PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(first_name,last_name,email,password)";
    $sql .= "VALUES('$first_name','$last_name','$email','$password')";
    confim(query($sql));
    set_message("Uspešno ste se registrovali! Molimo prijavite se!");
    redirect('login.php');
}
//VALIDACIJA ZA PRIJAVU
function validate_user_login(){
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email    = clean($_POST['email']);
        $password = clean($_POST['password']);
        if(empty($email)){
            $errors[] = "Polje za Email ne sme biti prazno!";
        }
        if(empty($password)){
            $errors[] = "Polje za Lozinku ne sme biti prazno!";
        }
        if(empty($errors)){
            if (user_login($email, $password)) {
                redirect('index.php');
            } else {
                $errors[] = "Vaša Email adresa ili lozinka nisu tačni. Molimo Vas da pokušate ponovo";
            }
        }
    }
    if(!empty($errors)){
        foreach($errors as $error){
            echo '<div class="alert"><i class="fa fa-exclamation-triangle">'. $error .'</i></div>';
        }
    }
}
//STRANICA ZA PRIJAVU
function user_login($email,$password){
    $email    = filter_var($email,FILTER_SANITIZE_EMAIL);
    $password = filter_var($password,FILTER_SANITIZE_STRING);

    $query  = "SELECT * FROM users WHERE email = '$email'";
    $result = query($query);

    if($result->num_rows == 1){
        $data = $result->fetch_assoc();
        if(password_verify($password, $data['password'])){
            $_SESSION['email'] = $email;
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
//UZIMANJE ID
function get_user($id = NULL){
    if ($id != NULL) {
        $query = "SELECT * FROM users WHERE id=" . $id;
        $result = query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "Korisnik nije pronadjen.";
        }
    } else {
        $query = "SELECT * FROM users WHERE email='". $_SESSION['email']."'";
        $result = query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "Korisnik nije pronadjen.";
        }
    }
}
//PROMENA LOZINKE
function update_password($password,$new_password){
    $new_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password = '$password', password= '$new_password' WHERE email ='". $_SESSION['email']."'"; 
    confim(query($sql));
    redirect("logout.php");
}
//VALIDACIJA OGLASA
function validate_create_ad(){
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $user_id      = clean($_POST['user_id']);
        $title        = ucfirst(strtolower(clean($_POST['title'])));
        $price        = clean($_POST['price']);
        $used         = clean($_POST['used']);
        $description  = clean($_POST['description']);
        $place        = ucwords(strtolower(clean($_POST['place'])));
        $phone_number = clean($_POST['phone_number']);
        $category     = clean($_POST['category']);
        $email        = clean($_POST['email']);
        
        $image1 = $_FILES['image1']['name'];
        $tmp_name1 = $_FILES["image1"]["tmp_name"];
        $image2 = $_FILES['image2']['name'];
        $tmp_name2 = $_FILES["image2"]["tmp_name"];
        $image3 = $_FILES['image3']['name'];
        $tmp_name3 = $_FILES["image3"]["tmp_name"];
        $image4 = $_FILES['image4']['name'];
        $tmp_name4 = $_FILES["image4"]["tmp_name"];
        $image5 = $_FILES['image5']['name'];
        $tmp_name5 = $_FILES["image5"]["tmp_name"];

        $data = [$image1,$image2,$image3,$image4,$image5];
        $location = "uploads/";

        $images1 = $location . rand(). $image1;
        $images2 = $location . rand(). $image2;
        if(isset($image3)){
            $images3 = $location . rand() . $image3;
        }else{
            $images3 = $location . $image3; 
        }
        if(isset($image4)){
            $images4 = $location . rand() . $image4;
        }else{
            $images4 = $location . $image4; 
        }
        if(isset($image5)){
            $images5 = $location . rand() . $image5;
        }else{
            $images5 = $location . $image5; 
        }


        move_uploaded_file($tmp_name1, $images1);
        move_uploaded_file($tmp_name2, $images2);
        move_uploaded_file($tmp_name3, $images3);
        move_uploaded_file($tmp_name4, $images4);
        move_uploaded_file($tmp_name5, $images5);

        if(strlen($title) > 200){
            $errors[] = "Naslov ne može da sadrži više od 200 karaktera!"; 
        }
        if(strlen($phone_number) > 10){
            $errors[] = "Broj telefona ne može da bude duži od 10 brojeva!"; 
        }
        if(!empty($errors)){
            foreach($errors as $error){
                echo '<div class="alter">'. $error . '</div>';
            }
        }
        else{
            $user_id     = $user_id;
            $title       = filter_var($title, FILTER_SANITIZE_STRING);
            $price       = $price;
            $used        = filter_var($used, FILTER_SANITIZE_STRING);
            $description = filter_var($description, FILTER_SANITIZE_STRING);
            $place       = filter_var($place, FILTER_SANITIZE_STRING);
            $phone_number= filter_var($phone_number, FILTER_SANITIZE_STRING);
            $category    = filter_var($category, FILTER_SANITIZE_STRING); 
            $email       = $email;         
            create_ad($user_id,$title,$price,$used,$description,$place,$phone_number,$category,$email,$images1,$images2,$images3,$images4,$images5);
        }
    }
}
//KREIRANJE OGLASA
function create_ad($user_id,$title,$price,$used,$description,$place,$phone_number,$category,$email,$images1,$images2,$images3,$images4,$images5){
    $user_id      = $user_id;
    $title        = ucfirst(strtolower(escape($title)));
    $price        = $price;
    $used         = escape($used);
    $description  = escape($description);
    $place        = ucfirst(strtolower(escape($place)));
    $phone_number = $phone_number;
    $category     = escape($category);
    $email        = $email;

    $sql = "INSERT INTO advertisement(user_id,title,price,used,description,place,phone_number,category,email,image1,image2,image3,image4,image5)";
    $sql .= "VALUES('$user_id','$title','$price','$used','$description','$place','$phone_number','$category','$email','$images1','$images2','$images3','$images4','$images5')";
    confim(query($sql));
    redirect('index.php');
}
//BRISANJE OGLASA
function delete_ad($id){
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $query = "SELECT * FROM advertisement WHERE id = $id";
        $result = query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                for($i = 1; $i <= 5; $i++){
                    unlink($row['image'.$i.'']);
                }
                $sql = "DELETE FROM advertisement WHERE id= $id";
                confim(query($sql));
            }
        }
    }
}
//BRISANJE OGLASA NAKON 30 DANA
function delete_ad_after30days($id){
    $query = "SELECT * FROM advertisement WHERE id = $id";
    $result = query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            for($i = 1; $i <= 5; $i++){
                unlink($row['image'.$i.'']);
            }
        }
    }
    $query_delete = "DELETE FROM advertisement WHERE id ='$id'";
    confim(query($query_delete));
    redirect('index.php');
}
//UPDATE OGLASA
function isset_update_ad($id){
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $title        = ucfirst(strtolower(clean($_POST['title'])));
        $price        = clean($_POST['price']);
        $used         = clean($_POST['used']);
        $description  = clean($_POST['description']);
        $place        = ucwords(clean($_POST['place']));
        $phone_number = clean($_POST['phone_number']);
        $category     = clean($_POST['category']);

        $img1 = $_FILES['image1']['name'];
        $tmp_name1 = $_FILES["image1"]["tmp_name"];
        $img2 = $_FILES['image2']['name'];
        $tmp_name2 = $_FILES["image2"]["tmp_name"];
        $img3 = $_FILES['image3']['name'];
        $tmp_name3 = $_FILES["image3"]["tmp_name"];
        $img4 = $_FILES['image4']['name'];
        $tmp_name4 = $_FILES["image4"]["tmp_name"];
        $img5 = $_FILES['image5']['name'];
        $tmp_name5 = $_FILES["image5"]["tmp_name"];

        $data = [$img1,$img2,$img3,$img4,$img5];
        $location = "uploads/";

        $image1 = $location . $img1;
        $image2 = $location . $img2;
        if(isset($img3)){
            $image3 = $location . rand() . $img3;
        }else{
            $image3 = $location . $img3; 
        }
        if(isset($img4)){
            $image4 = $location . rand() . $img4;
        }else{
            $image4 = $location . $img4; 
        }
        if(isset($img5)){
            $image5 = $location . rand() . $img5;
        }else{
            $image5 = $location . $img5; 
        };

        move_uploaded_file($tmp_name1, $image1);
        move_uploaded_file($tmp_name2, $image2);
        move_uploaded_file($tmp_name3, $image3);
        move_uploaded_file($tmp_name4, $image4);
        move_uploaded_file($tmp_name5, $image5);

        update_ad($id,$title,$price,$used,$description,$place,$phone_number,$category,$image1,$image2,$image3,$image4,$images5);
    }
}
function update_ad($id,$title,$price,$used,$description,$place,$phone_number,$category,$image1,$image2,$image3,$image4,$image5){
    $sql = "UPDATE advertisement SET title = '$title', price = '$price', used = '$used', description = '$description', place = '$place', phone_number = '$phone_number', category = '$category', image1 = '$image1', image2 = '$image2', image3 = '$image3', image4 = '$image4', image5 = '$image5' WHERE id = $id"; 
    confim(query($sql));
    redirect("my_ad.php");
} 
//FORMAT ZA DATUM
function dates($date){
    $dat = strtotime($date);
    return date("d.m.Y", $dat); 
}
//IZLISTAVANJE SVIH OGLASA
function fetch_all_ad(){
    $query = "SELECT * FROM advertisement ORDER BY id DESC";
    $result = query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $current_date = date("Y-m-d H:i:s");
            $date_created_ad = $row['time'];
            $after_30days = date("Y-m-d H:i:s" , strtotime($date_created_ad .'+30 day'));
            $before_30days = date("Y-m-d H:i:s" , strtotime($after_30days .'-30 day'));
            $id = $row['id'];

            if($current_date >= $after_30days && $date_created_ad == $before_30days){
                delete_ad_after30days($id);
            }
            echo "<div class='container'>
                    <div class='row-wrapper'>
                        <div class='row cols-xs-space cols-md-space cols-md-space'>
                            <div class='shop-default shop-cards shop-tech col-12 masonry-container'>
                                <div class='row'> 
                                    <div class='col-md-12 col-lg-6 mb-4 masonry-item'>
                                        <div class='block product z-depth-2-top z-depth-2--hover'>
                                            <div class='block-image mt-4 text-center' style='border-bottom:1px solid #c7c7c7'>
                                                <a href='ad.php?id=".$row['id']."'>
                                                    <img src='".$row['image1']."' class='img-center hidden-xs-down image_dimension'>
                                                </a>  
                                            </div>
                                            <div class='block-body text-left card-box'>
                                                <h4 class='heading strong-400 text-capitalize title_ad'>
                                                    <p class='text-dark'>". $row['title']."</p>
                                                </h4>
                                                <p class='product-description strong-400 description_ad'>
                                                    <span class='small text-default'></span><small>". $row['place']."</small>
                                                </p>
                                                <p class='product-description strong-400'>
                                                    <span class='small text-default'></span><small>". dates($row['time'])."</small>
                                                </p>
                                                <p class='product-description strong-400'>
                                                    <span class='text-default description'>". $row['price']." din</span>
                                                </p>
                                            </div>
                                        </div>            
                                    </div>
                                </div>              
                            </div>
                        </div>
                    </div>
                </div>";
        }
    }else{
        echo "<div class='container col-lg-4 container_image'>
                <div class='block-image mt-4 text-center'>
                    <img src='style/img/add.png' class='img-center hidden-xs-down'>
                </div>
                <p class='product-description strong-400 text-center'>
                    <span class='text-default'>Nema jos oglasa.</span>
                </p>
              </div>";
    }
}
//UZIMANJE ID IZ OGLASA
function get_ad($id = NULL){
    if ($id != NULL) {
        $query = "SELECT * FROM advertisement WHERE user_id=" . $id;
        $result = query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "Oglas nije pronadjen.";
        }
    }
}
//OGLAS NA SRANICI my_ad.php
function my_ad(){
    $query = "SELECT * FROM advertisement ORDER BY id DESC";
    $result = query($query);
    $count_rows = 1;
    $count = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if($row['email'] == $_SESSION['email']){
                echo "<div class='container'>
                        <div class='row-wrapper'>
                            <div class='row cols-xs-space cols-md-space cols-md-space'>
                                <div class='shop-default shop-cards shop-tech col-12 masonry-container'>
                                    <div class='row'> 
                                        <div class='col-md-12 col-lg-6 mb-4 masonry-item'>
                                            <div class='block product z-depth-2-top z-depth-2--hover'>
                                                <div class='block-image mt-4 text-center' style='border-bottom:1px solid #c7c7c7'>
                                                    <a href='ad.php?id=".$row['id']."'>
                                                        <img src='".$row['image1']."' class='img-center hidden-xs-down image_dimension'>
                                                    </a>    
                                                </div>
                                                <div class='block-body text-left card-box' >
                                                    <h4 class='heading strong-400 text-capitalize' style='margin-bottom:-20px;margin-top:10px'>
                                                        <p class='text-dark'>". $row['title']."</p>
                                                    </h4>
                                                    <p class='product-description strong-400 place'>
                                                        <span class='small text-default'></span><small>". $row['place']."</small>
                                                    </p>
                                                    <p class='product-description strong-400'>
                                                        <span class='small text-default'></span><small>". dates($row['time'])."</small>
                                                    </p>
                                                    <p class='product-description strong-400'>
                                                        <span class='text-default' style='color:red; font-size:20px'>". $row['price']." din</span>
                                                    </p>
                                                </div>
                                            </div>            
                                        </div>
                                    </div>                 
                                </div>
                            </div>
                        </div>
                    </div>";
                
                $count++;
            }
            else{
                if($count_rows == $result->num_rows){
                    if($count == 0){
                        echo "<div class='container col-lg-4 container_image'>
                                <div class='block-image mt-4 text-center'>
                                        <img src='style/img/add.png' class='img-center hidden-xs-down'>
                                </div>
                                <p class='product-description strong-400' style='text-align:center;>
                                    <span class='text-default'>Nemate oglasa.</span>
                                </p>
                                <a href='new_ad.php' class='form-control form-btn dugme'>Novi oglas</a>
                              </div>";
                    } 
                }
            }
        $count_rows++;
        }
    }else{
        echo "<div class='container col-lg-4 container_image'>
                <div class='block-image mt-4 text-center'>
                        <img src='style/img/add.png' class='img-center hidden-xs-down'>
                </div>
                <p class='product-description strong-400' style='text-align:center;>
                    <span class='text-default' >Nemate oglasa.</span>
                </p>
                <a href='new_ad.php' class='form-control form-btn form-btn dugme'>Novi oglas</a>
            </div>";
    }
}
//OTVARANJE OGLASA NA STRANICI ad.php
function one_ad($id){
    $query = "SELECT * FROM advertisement";
    $result = query($query);   

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if($row['id'] == $id){
                if($row['email'] == $_SESSION['email']){        
                    echo "<div class='container col-lg-4 text-right query'>
                            <form action='delete_ad.php?id=".$id."' method='POST'>
                                <a href='update_ad.php?id=".$id."' class='update'>Izmeni</a>
                                <input type='submit' name='submit' value='/  Obriši' style='border:none; background-color:white; color:blue;'>                                
                            </form>
                          </div>";
                }             
                echo "<div class='container'>
                        <div class='row-wrapper'>
                            <div class='row cols-xs-space cols-md-space cols-md-space'>
                                <div class='shop-default shop-cards shop-tech col-12 masonry-container'>
                                    <div class='row'> 
                                        <div class='col-md-12 col-lg-6 mb-4 masonry-item'>
                                            <div class='block product z-depth-2-top z-depth-2--hover'>
                                                <div class='block-image mt-4 text-center' style='border-bottom:1px solid #c7c7c7'>
                                                    <button id='previous' class='btn arrows previous' id='previous'><img src='style/img/prev.png'></button>
                                                    <button id='next' class='btn arrows next' id='next'><img src='style/img/next.png'></button>
                                                    <input type='hidden' id='image1' value='".$row['image1']."'>
                                                    <input type='hidden' id='image2' value='".$row['image2']."'>
                                                    <input type='hidden' id='image3' value='".$row['image3']."'>
                                                    <input type='hidden' id='image4' value='".$row['image4']."'>
                                                    <input type='hidden' id='image5' value='".$row['image5']."'>

                                                    <img src='".$row['image1']."' id='image' class='img-center hidden-xs-down image_dimension'> 
                                                </div>
                                                <div class='block-body text-center' >
                                                    <h4 class='heading strong-400 text-capitalize title_ad'>
                                                        <p class='text-dark'>". $row['title']."</p>
                                                    </h4>
                                                    <p class='product-description strong-400 description_ad'>
                                                        <span class='small text-default'></span><small>"."(". $row['used'].")"."</small>
                                                    </p>
                                                    <p class='product-description strong-400'>
                                                        <span class='text-default price'>". $row['price']." din</span>
                                                    </p>
                                                    <p class='product-description strong-400'>
                                                        <span class='small text-default' style='margin-top:-12px'></span><small>". $row['place']."</small>
                                                    </p>
                                                    <p class='product-description text-left strong-400' style='padding:10px'>
                                                        <span class='text-default'>". $row['description']."</span>
                                                    </p>
                                                </div>
                                            </div>            
                                        </div>
                                    </div>               
                                </div>
                            </div>
                        </div>
                    </div> 
                    <script>
                        var prev = document.getElementById('previous');
                        var next = document.getElementById('next');
                        var image1 = document.getElementById('image1').value;
                        var image2 = document.getElementById('image2').value;
                        var image3 = document.getElementById('image3').value;
                        var image4 = document.getElementById('image4').value; 
                        var image5 = document.getElementById('image5').value;          

                        if(image1 !== 'uploads/' && image2 !== 'uploads/' && image3 !== 'uploads/' && image4 !== 'uploads/' && image5 !== 'uploads/' ){
                            var array_image = [image1,image2,image3,image4,image5];                                                                                                         
                        }else if(image5 == 'uploads/' && image4 == 'uploads/' && image3 == 'uploads/' ){
                            var array_image = [image1,image2];                                                                      
                        }else if(image5 == 'uploads/' && image4 == 'uploads/'){
                            var array_image = [image1,image2,image3];                                                                                                           
                        }else if(image5 == 'uploads/' && image3 == 'uploads/'){
                            var array_image = [image1,image2,image4];                                                                                                           
                        }else if(image5 == 'uploads/'){
                            var array_image = [image1,image2,image3,image4];                                                                                                           
                        }else if(image4 == 'uploads/' && image3 == 'uploads/'){
                            var array_image = [image1,image2,image5];                                                                                                           
                        }else if(image4 == 'uploads/'){
                            var array_image = [image1,image2,image3,image5];                                                                               
                        }else if(image3 == 'uploads/'){
                            var array_image = [image1,image2,image4,image5];                                                                               
                        }else if(image1 && image2 !== 'uploads/'){
                            var array_image = [image1,image2];                                                                               
                        }
                                                                                              
                        var i = 0;

                        //next btn
                        next.addEventListener('click', function(){
                        i++;
                        if(i > array_image.length - 1){
                                i = 0;
                        }
                        document.getElementById('image').src = array_image[i];
                        })

                        //prev btn
                        prev.addEventListener('click', function(){
                            i--;
                            if(i < 0){
                                i = array_image.length - 1;
                            }
                            document.getElementById('image').src = array_image[i];
                        })
                    </script>";
                    
                $user_id = $row['user_id'];
                $user_phone = $row['phone_number'];
                $id = $row['id'];
                $email = $row['email']; 
            }
        }
    }

    $query = "SELECT * FROM save_advertisement WHERE advertisement_id = '$id'";
    $result = query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id2   = $row['advertisement_id'];
            if($row['email2'] !== ""){
                $email2 = $row['email2'];
            }else {        
                $email2 = NULL;
            }
        }
    }else{
        $id2 = 2;
    }
    data($user_id,$user_phone,$id,$id2,$email2,$email);
}
//PODACI KORISNIKA ISPOD OGLASA
function data($user_id,$user_phone,$id,$id2,$email2,$email){
    $query = "SELECT * FROM users";
    $result = query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if($row['id'] == $user_id){
                echo "<div class='container '>
                        <div class='row-wrapper'>
                            <div class='row cols-xs-space cols-md-space cols-md-space'>
                                <div class='shop-default shop-cards shop-tech col-12 masonry-container'>
                                    <div class='row'> 
                                        <div class='col-md-12 col-lg-6 mb-4 masonry-item'>
                                            <div class='block product z-depth-2-top z-depth-2--hover' style='padding-left:10px'>
                                                <div class='block-body text-left' >
                                                    <p class='product-description strong-400'>
                                                        <p class='text-dark' style='padding-top:15px'>Ime: ". $row['first_name']."</p>
                                                    </p><hr>                                               
                                                    <p class='product-description strong-400'>
                                                        <span class=' text-default'>Prezime: ". $row['last_name']."</span>
                                                    </p><hr>
                                                    <p class='product-description strong-400'>
                                                        <span class='text-default'>Email: ". $row['email']."</span>
                                                    </p><hr>
                                                    <p class='product-description strong-400'>
                                                        <span class='text-default'>Broj telefona: ". $user_phone ."</span>
                                                    </p><hr>
                                                    <p class='product-description strong-400'>
                                                        <span class='text-default'>Član od: ". dates($row['date'])."</span>
                                                    </p><hr>   
                                                    <p class='product-description strong-400'>
                                                        <a class='fa fa-list' style='font-size:22px' href='all_ad.php?id=".$row['id']."'> Svi oglasi</a>
                                                    </p>".                                                                                 
                                                    $id1 = '';  
                                                    if(isset($_SESSION['email']) && $email !== $_SESSION['email']){
                                                    
                                                        if($id2 == $id && $email2 == $_SESSION['email']){
                                                            echo "<hr>                                                  
                                                                <form action='delete_saved_ad.php' method='POST' style='margin:-50px 0 0 0'>
                                                                    <img src='style/img/black_star.png' style='margin-bottom:7px'><input type='submit' class='' name='submit' value='Ukloni oglas' style='border:none; background-color:white;color:black;'>
                                                                    <input type='hidden' name='ad_id' value='$id'>                                                       
                                                                </form>                                         
                                                            </div></div></div></div></div></div></div></div>";
                                                        }else{                                                   
                                                            echo "<hr>
                                                                <form action='save_advertisement.php' method='POST' style='margin:-50px 0 0 0'>
                                                                <img src='style/img/star.png' style='margin-bottom:7px'><input type='submit' value='Sacuvaj oglas' style='border:none; background-color:white;color:black'>
                                                                    <input type='hidden' name='advertisement_id' value='$id'> 
                                                                </form>
                                                            </div></div></div></div></div></div></div></div>";}
                                                        }else{
                                                            echo "      
                                                        </div>            
                                                    </div>
                                                </div>               
                                            </div>
                                        </div>
                                    </div>
                                </div>";                     
                }
            }
        }
    }
}
//CUVANJE OGLASA
function query1(){
    $query = "SELECT * FROM `save_advertisement`
              INNER JOIN `advertisement` ON `save_advertisement`.`advertisement_id`=`advertisement`.`id`";
    confim(query($sql));
    $result = query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id1 = 2;
            if($id1== 1){
                echo "
                <form action='save_advertisement.php' method='POST' style='margin:-50px 0 0 0   '>
                    <i class='fa fa-star'><input type='submit' value='Sacuvaj oglas' style='border:none; background-color:white;color:black'></i>
                    <input type='hidden' name='advertisement_id' value='$id'> 
                </form>
            </p><hr>
            <p class='product-description strong-400'>
                <a class='fa fa-list' style='font-size:22px' href='all_ad.php'> Svi oglasi</a>
            </p>
        ";
            }else{
                echo "             
                <form action='save_advertisement.php' method='POST' style='margin:-50px 0 0 0   '>
                    <i class='fa fa-star'><input type='submit' value='Ukloni oglas' style='border:none; background-color:white;color:black'></i>
                    <input type='hidden' name='advertisement_id' value='$id'> 
                </form>
            </p><hr>
            <p class='product-description strong-400'>
                <a class='fa fa-list' style='font-size:22px' href='all_ad.php'> Svi oglasi</a>
            </p>";                                                         
            }
        }
    }
}
//INSERT SACUVANOG OGLASA
function save_advertisement(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $advertisement_id = $_POST['advertisement_id'];
    $email = $_SESSION['email'];

    $sql = "INSERT INTO save_advertisement(advertisement_id,email2)";
    $sql .= "VALUES('$advertisement_id','$email')";
    confim(query($sql));
    redirect('save_advertisement.php');
    }
}
function delete_saved_advertisement($id){
    if(isset($_POST['submit'])){
        $sql = "DELETE FROM `save_advertisement` WHERE advertisement_id = $id";
        confim(query($sql));
        redirect('save_advertisement.php');
    }
}
//STRANICA save_advertisement.php
function save_advertisement_page(){
    $query = "SELECT * FROM `save_advertisement`
              INNER JOIN `advertisement` ON `save_advertisement`.`advertisement_id`=`advertisement`.`id`";
    $result = query($query);
    if ($result->num_rows > 0) {
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            
            if($row['email2'] == $_SESSION['email'] && $row['advertisement_id'] == $row['id']){
            echo "<div class='container'>
                    <div class='row-wrapper'>
                        <div class='row cols-xs-space cols-md-space cols-md-space'>
                            <div class='shop-default shop-cards shop-tech col-12 masonry-container'>
                                <div class='row'> 
                                    <div class='col-md-12 col-lg-6 mb-4 masonry-item'>
                                        <div class='block product z-depth-2-top z-depth-2--hover'>                        
                                            <div class='block-image mt-4 text-center'style='border-bottom:1px solid #c7c7c7'>
                                                <a href='ad.php?id=".$row['id']."'>
                                                    <img src='".$row['image1']."' class='img-center hidden-xs-down image_dimension'>
                                                </a>    
                                            </div>
                                            <div class='block-body text-left card-box'>
                                                <h4 class='heading strong-400 text-capitalize title_ad'>
                                                    <p class='text-dark'>". $row['title']."</p>
                                                </h4>
                                                <p class='product-description strong-400 place'>
                                                    <span class='small text-default'></span><small>". $row['place']."</small>
                                                </p>
                                                <p class='product-description strong-400'>
                                                    <span class='small text-default'></span><small>". dates($row['time'])."</small>
                                                </p>
                                                <p class='product-description strong-400'>
                                                    <span class='text-default price'>". $row['price']." din</span>
                                                </p>
                                            </div>
                                        </div>            
                                    </div>
                                </div>              
                            </div>
                        </div>
                    </div>
                </div>";
            }else{
                if($count >=  $result->num_rows )
                 echo "<div class='container col-lg-4 container_image'>
                <div class='block-image mt-4 text-center'>
                        <img src='style/img/add.png' class='img-center hidden-xs-down'>
                </div>
                <p class='product-description strong-400' style='text-align:center;>
                    <span class='text-default'>Jos uvek niste sacuvali oglase.</span>
                </p>
              </div>";
            }
            $count++;
        }
    }else{
        echo "<div class='container col-lg-4 container_image'>
        <div class='block-image mt-4 text-center'>
                <img src='style/img/add.png' class='img-center hidden-xs-down'>
        </div>
        <p class='product-description strong-400 text-center'>
            <span class='text-default'>Jos uvek niste sacuvali oglase.</span>
        </p>
      </div>";
    }
}
//STRANICA SA OGLASIMA KOJA SE OTVARA PRILIKOM LINKA
function all_ad($user_id){
    $query = "SELECT * FROM advertisement WHERE user_id= '$user_id' ORDER BY id DESC";
    $result = query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='container'>
                    <div class='row-wrapper'>
                        <div class='row cols-xs-space cols-md-space cols-md-space'>
                            <div class='shop-default shop-cards shop-tech col-12 masonry-container'>
                                <div class='row'> 
                                    <div class='col-md-12 col-lg-6 mb-4 masonry-item'>
                                        <div class='block product z-depth-2-top z-depth-2--hover'>
                                            <div class='block-image mt-4 text-center'style='border-bottom:1px solid #c7c7c7'>
                                                <a href='ad.php?id=".$row['id']."'>
                                                    <img src='".$row['image1']."' class='img-center hidden-xs-down image_dimension'>
                                                </a>                                                                                    
                                            </div>
                                            <div class='block-body text-left card-box' >
                                                <h4 class='heading strong-400 text-capitalize title_ad'>
                                                    <p class='text-dark'>". $row['title']."</p>
                                                </h4>
                                                <p class='product-description strong-400 place'>
                                                    <span class='small text-default'></span><small>". $row['place']."</small>
                                                </p>
                                                <p class='product-description strong-400'>
                                                    <span class='small text-default'></span><small>". dates($row['time'])."</small>
                                                </p>
                                                <p class='product-description strong-400'>
                                                    <span class='text-default price'>". $row['price']." din</span>
                                                </p>
                                            </div>
                                        </div>            
                                    </div>
                                </div>                 
                            </div>
                        </div>
                    </div>
                </div>";
        }
    }
}
//PRETRAGA PO KATEGORIJAMA
function category_search($category){
    $query = "SELECT * FROM advertisement WHERE category = '$category' ORDER BY price DESC";
    $result = query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='container'>
                    <div class='row-wrapper'>
                        <div class='row cols-xs-space cols-md-space cols-md-space'>
                            <div class='shop-default shop-cards shop-tech col-12 masonry-container'>
                                <div class='row'> 
                                    <div class='col-md-12 col-lg-6 mb-4 masonry-item'>
                                        <div class='block product z-depth-2-top z-depth-2--hover'>                        
                                            <div class='block-image mt-4 text-center'style='border-bottom:1px solid #c7c7c7'>
                                                <a href='ad.php?id=".$row['id']."'>
                                                    <img src='".$row['image1']."' class='img-center hidden-xs-down image_dimension'>
                                                </a>    
                                            </div>
                                            <div class='block-body text-left card-box'>
                                                <h4 class='heading strong-400 text-capitalize title_ad'>
                                                    <p class='text-dark'>". $row['title']."</p>
                                                </h4>
                                                <p class='product-description strong-400 place'>
                                                    <span class='small text-default'></span><small>". $row['place']."</small>
                                                </p>
                                                <p class='product-description strong-400'>
                                                    <span class='small text-default'></span><small>". dates($row['time'])."</small>
                                                </p>
                                                <p class='product-description strong-400'>
                                                    <span class='text-default price'>". $row['price']." din</span>
                                                </p>
                                            </div>
                                        </div>            
                                    </div>
                                </div>              
                            </div>
                        </div>
                    </div>
                </div>";
        }
    }else{
        echo "<div class='container col-lg-4 container_image'>
                <div class='block-image mt-4 text-center'>
                        <img src='style/img/add.png' class='img-center hidden-xs-down'>
                </div>
                <p class='product-description strong-400 text-center'>
                    <span class='text-default'>Nema jos oglasa za ovu kategoriju.</span>
                </p>
              </div>";
    }
}

