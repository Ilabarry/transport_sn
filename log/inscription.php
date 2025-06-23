<?php
if(isset($_POST['inscrire']))
{
    
    $prenom=$_POST['prenom'];
    $nom=$_POST['nom'];
    $email=$_POST['email'];
    $mot_pass=password_hash($_POST['pass'],PASSWORD_DEFAULT) ;
    $ville_origine=$_POST['ville_origine'];
    $ville_domicile=$_POST['ville_domicille'];
    $telephone=$_POST['telephone'];
    $role=$_POST['role'];
    if (empty($prenom) || empty($nom) || empty($email) || empty($mot_pass) || empty($ville_origine) || empty($ville_domicile) || empty($telephone)) {
            echo "<b class='text-danger b_form'> Mercie de bien remplir tous les champs formulaire</b>";
        }
    else {
        try{
            require "../hfc/config.php";
            $sql = "INSERT INTO users(prenom,nom,email,mot_pass,ville_origine,ville_actuelle,telephone,role)
            VALUES(:pre,:nom,:eml,:pwd,:vor,:vac,:tel,:rol)";
            $stmt = $requete->prepare($sql);
            $stmt->bindParam(':pre',$prenom,PDO::PARAM_STR);
            $stmt->bindParam(':nom',$nom,PDO::PARAM_STR);
            $stmt->bindParam(':eml',$email,PDO::PARAM_STR);
            $stmt->bindParam(':pwd',$mot_pass,PDO::PARAM_STR);
            $stmt->bindParam(':vor',$ville_origine,PDO::PARAM_STR);
            $stmt->bindParam(':vac',$ville_domicile,PDO::PARAM_STR);
            $stmt->bindParam(':tel',$telephone,PDO::PARAM_STR);
            $stmt->bindParam(':rol',$role,PDO::PARAM_STR);
            
            $stmt->execute();
            echo "<script>alert('Inscription réussi avec success');</script>";
            echo "<script>window.location.href='connexion.php'</script>";
        }
        catch(PDOException $e)
        {
           echo "Erreur lors de la connexion : " .$e->getMessage();
        }
    }
    
}
?>


<!doctype html>
<html lang="en">
  <head>
    <title>Pages-d'-inscription</title>
    <link rel="icon" type="image" href="senvoyagee.png">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
    <style>
        .body{
            padding:0;
            margin:0;
            box-sizing:border-box;
            background:#2ecc71 ;
        }
        .title-general_h2{
        background:#2ecc71 ;
        border-bottom: 3px solid red;
        padding:5px 20px;
        border-radius: 0 50% 0 50%;
        color:yellow;
        font-weight: 400;
        font-style: normal;

    }
    .row_from{
        background:rgba(255, 255, 255, 0.5) ;
        border-radius:20px;
        
    }

    .title_1{color:#2ecc71 ;text-transform:uppercase;}
    .title_2{color:yellow;}
    .title_3{color:red;}
    .display-4{
        position:absolute;
        background:rgba(255, 255, 255, 0.9) ;
        padding:10px;
        border-radius:15px;
        margin-left:10%;
    }
    input[type=text],input[type=email],input[type=password],select[id=inputState]{
        background:transparent ;
        border:none;
        border-bottom:2px solid  white;

    }
    input[type=text],input[type=email],input[type=password],select[id=inputState]:focus{
        background:transparent !important;
        border:none !important;
        border-bottom:2px solid  white !important;
        outline:none !important;
        box-shadow:none !important;
    }
    input[type=text],input[type=email],input[type=password],select[id=inputState]:active{
        box-shadow:2px 2px 3px #2ecc71 !important;
    }
    input[type=submit]{
        background:#2ecc71;
        padding:10px 90px;
        border:none;
        border-radius:15px;
        color:white;
        transition:0.5s;
        font-weight:900;
    }
    input[type=submit]:hover{
        font-size:25px;
        color:yellow;
    }
    label{
        color:white;
        font-weight:900;
    }
    .b_form{
        position:absolute;
        top:350px;
        left:0;
        width:100%;
        border:none;
        z-index:1;
        display:flex;
        align-items:center;
        justify-content:center;
    }
    </style>

  </head>
  <body class="body">
      <div class="container">
<br>
        <div class="row">
            <div class="col-sm-2 col-md-3 col-lg-3"></div>
            <div class="col-sm-8 col-md-6 col-lg-6 row_from p-5">

                <div class="img">
                    <h1 class="display-4 text-center mt-5"><span class="title_1">sén</span><span class="title_2">voy</span><span class="title_3">age</span></h1>
                    <img src="../images/img-inscription.jpg" alt="" width="100%" height="200px">
                    
                    <h1 class="title-general_h2 text-center">INSCRIPTION</h1><br><br>
                </div>

                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="inputEmail4 text-white">Prenom</label>
                        <input type="text" class="form-control" id="inputEmail4" placeholder="Votre prénom" name="prenom">
                        </div>
                        <div class="form-group col-md-6">
                        <label for="inputPassword4 text-white">Nom</label>
                        <input type="text" class="form-control" id="inputPassword4"placeholder="Votre nom de famille" name="nom">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="inputEmail4 text-white">Adresse e-mail</label>
                        <input type="email" class="form-control" id="inputEmail4" placeholder="xzy@gmail.com" name="email">
                        </div>
                        <div class="form-group col-md-6">
                        <label for="inputPassword4 text-white">Mot de passe</label>
                        <input type="password" class="form-control" id="inputPassword4" placeholder="Complex password" name="pass">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress text-white">Ville d'origine</label>
                        <input type="text" class="form-control" id="inputAddress" placeholder="Région natale" name="ville_origine">
                    </div>
                    <div class="form-group">
                        <label for="inputAddress2 text-white">Ville actuelle</label>
                        <input type="text" class="form-control" id="inputAddress2" placeholder="Région de domicile" name="ville_domicille">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputCity text-white">Numéro de téléphone</label>
                            <input type="text" class="form-control" id="inputCity" placeholder="7x xxx xx xx" name="telephone">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputState text-white">êtes-vous un conducteur :</label>
                            <select id="inputState" class="form-control" name="role">
                                <option value="client">Je le suis pas</option>
                                <option value="conducteur">Je le suis</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row text-center h4">
                        <input type="submit" name="inscrire" id="" value="S'inscrire">
                    </div>
                </form>

            </div>
            <div class="col-sm-2 col-md-3 col-lg-3"></div>
        </div>
       
    </div><br>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>


