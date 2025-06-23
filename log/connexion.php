<?php  
require "../hfc/config.php";
session_start();  
     if(isset($_POST["connexion"]))  
     {  
          if(empty($_POST["email"]))  
          {  
            echo "<b class='text-danger b_form'>Identifiant  incorrect </b>";
          }  
          elseif (empty($_POST["password"])) {
            echo "<b class='text-danger b_form'> Mot de passe incorrect</b>";
          }
          else  
          {  
               $query = "SELECT * FROM users WHERE email = :email";  
               $statement = $requete->prepare($query); 
               $statement->execute(['email'     =>     $_POST["email"]]);
               $user=$statement->fetch(PDO::FETCH_ASSOC)  ;
                
               if ($user && password_verify($_POST['password'],$user['mot_pass'])) {
                    $_SESSION["email"] = $_POST["email"]; 
                    $_SESSION["prenom"] = $_POST["prenom"]; 
                    $_SESSION["nom"] = $_POST["nom"]; 
                    $_SESSION["id_users"] = $user["id"];
                    $_SESSION["role"] = $user["role"];
                    header("location: ../index.php"); 
                    exit();
               }else  
               {  
                echo "<b class='text-danger b_form'>Identifiant ou Mot de passe incorrect</b>";
                }
           }  
    }  

?> 


<!doctype html>
<html lang="en">
  <head>
    <title>Pages-de-connexion</title>
    <link rel="icon" type="image" href="senvoyagee.png">
<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
    <style>
        body{
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
                    
                    <h1 class="title-general_h2 text-center">CONNEXION</h1><br><br>
                </div>

                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                    <div class="form">
                        <div class="form-group col-md-10">
                        <label for="inputEmail4 text-white">Adresse e-mail</label>
                        <input type="email" class="form-control" id="inputEmail4" placeholder="xzy@gmail.com avec lequel vous avez inscrire" name="email">
                        </div>
                        <div class="form-group col-md-10">
                        <label for="inputPassword4 text-white">Mot de passe</label>
                        <input type="password" class="form-control" id="inputPassword4" placeholder="mot de pass lors de votre inscription"name="password">
                        </div>
                    </div>
                    
                    <div class="form-group row text-center h4">
                        <input type="submit" name="connexion" id="" value="Se connecter">
                    </div>
                    <p><b>L'inscription est obligatoire avant de se connecter pour la première fois </b><a href="inscription.php">lien ver le formulaire d'inscription...</a></p>
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