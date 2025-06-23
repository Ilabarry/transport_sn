<?php
 $host = "localhost";  
 $username = "root";  
 $password = "";  
 $database = "transport";  
 $message = "";  
 try  
 {  
      $requete = new PDO("mysql:host=$host; dbname=$database", $username, $password);  
      $requete->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  


    }  
    catch(PDOException $error)  
    {  
     $message = "Erreur de connexion à la base de données : " . $error->getMessage();
     die($message);  
    }  