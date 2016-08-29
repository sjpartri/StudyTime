<?php
// List of events
 $json = array();

 // Query that retrieves events
 $requete = "SELECT * FROM scholarship.events ORDER BY id";

 // connection to the database
 try {
 $bdd = new PDO('mysql:host=scholarship.deltachi.ca:3306;dbname=scholarship', 'scholarshipchair', 'Cornell1890');
 } catch(Exception $e) {
  exit('Unable to connect to database.');
 }
 // Execute the query
 $resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

 // sending the encoded result to success page
 echo json_encode($resultat->fetchAll(PDO::FETCH_ASSOC));

?>