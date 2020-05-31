<?php

class MyBDD
{
    // classe BDD.php pour modifier un objet de la table  (CRUD)
    // Création de la variable _db
    private $_db;

    // constructeur de la classe BDD ou l'on appelle la fonction setDb
    public function __construct($db){
        $this->setDb($db);
    }

    // Fonction ou l'on instancie la valeur de _db a l'objet PDO $db
    public function setDb(PDO $db){
        $this->_db = $db;
    }


}


?>