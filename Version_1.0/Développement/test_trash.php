<?php
// Création d'un tableau reliant alphabet et numéros
$alphaArray = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E',
6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L',
13 => 'M', 14 => 'N', 15 => '0', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T',
21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');

// Ici on boucle pour écrire toute les valeurs du tableaux et leur clé
foreach ($alphaArray as $key => $value) {
    echo "La clé n°".$key." référence la lettre ".$value;
    echo "<br>"; 
}

// Ici on echo la valeur equivaut a la clé n°1
echo $alphaArray[1];

// Création d'un tableau en HTML avec la requete SQL, on vient vérifier chaque ligne de la requete et on les incorpore au tableau
// via l'identifiant de la colonne choisi
// On créer le tableau puis on execute la requete, enfin on lit la requete executé pour incrémenter le tableau HTML
// Test tableau HTML A SUPPRIMER --------------------------------------------------------------------------------------------------->
echo "<table border=\"1\" classe=\"sqltab\"><tr><td> Nom </td><td> Prénom </td><td> Classe </td><td> Identifiant </td></tr>";
// $query1->execute(array('nom','prenom','classe','id_etudiant'));
while($row = $query1->fetch(PDO::FETCH_OBJ)) {
    echo "<tr><td>".$row->nom."</td><td>".$row->prenom."</td><td>".$row->classe."</td><td>".$row->id_etudiant."</td></tr>";
} // fin de while	
echo "</table>";
echo "<br>";

// Ecriture du tableau de données retourner depuis la base de données, c'est un tableau de tableau
// Test A SUPPRIMER -------------------------------------------------------------------------------------------->
$blop = $pdo->query($sql01);
var_dump($blop);
echo "<br>";

// Ecriture du tableau du nom des champs retourner depuis la base de données, c'est un tableau
// Test A SUPPRIMER -------------------------------------------------------------------------------------------->
$tabChamp1 = $queryTest->fetchAll(PDO::FETCH_ASSOC);
var_dump($tabChamp1);
echo "<br>";

// Bloc de code pour créer tous les titres du tableau excel
// On implémente les titres du tableau via la requete $queryChamp
// A FINIR ---------------------------------------------------------------------------------->
$tabChampTemp = $queryChamp->fetchAll(PDO::FETCH_ASSOC);
$tabChamp = array();
echo "<br>";
$y = 0;
foreach ($tabChampTemp as $key => $value) {
    array_push($tabChamp, $value['COLUMN_NAME']);
    echo $tabChamp[$y];
    echo "<br>";
    ++$y;
}

$sheetArray = array($row->nom,$row->prenom,$row->classe,$row->id_etudiant);

for ($i=1; $i <= count($nameChamp); $i++) {
    echo "<br>";
    for ($j=1; $j <= $x; $j++) { 
        $strLenCell = strlen($sheet->getCell($alphaArray[$i].$j)->getValue());
        if ($strLenCell > $strLenCell2) {
            $strLenCell2 = $strLenCell;
        }
    }
    // Application d'une taille de colonne
    $sheet->getColumnDimension($alphaArray[$i])->setAutoSize(true);
    // $sheet->getColumnDimension($alphaArray[$i])->setWidth($strLenCell2+2);
    echo $strLenCell2;
}
?>