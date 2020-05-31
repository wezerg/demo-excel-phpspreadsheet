<?php
// Importation du fichier autoload.php pour les extracts en excel
require __DIR__ . '/vendor/autoload.php';

// Importation des classes de création de fichier excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\Font;

// Création d'un tableau reliant alphabet et numéros
$alphaArray = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E',
6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L',
13 => 'M', 14 => 'N', 15 => '0', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T',
21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');

// Création d'une chaine de caractère de connexion
// Il faut modifier la chaine de caractère pour y entrer les informations de connexion à la BDD
try {
    $pdo = new PDO('mysql:host=localhost;dbname=16_09_19_php01',"root","") or die ("Attention, problème de connexion serveur");	
} catch (PDOExceptions $e) {
    echo "Impossible de se connecter a la base de données, abandon de la procédure.";
}

// Création d'une chaine de caractère qui représente la requete SQL des données voulues
$sql01 = "SELECT nom as Nom, prenom as Prenom, classe as Classe, id_etudiant as Identifiants FROM etudiants;";

// Préparation des requetes
$query1 = $pdo->query($sql01);
$tabTemp = $query1->fetchAll(PDO::FETCH_ASSOC);

// Création virtuelle du fichier excel
$spreadsheet = new Spreadsheet();

// Création d'un tableau pour les titres du tableau excel
// Ce tableau reprend les indexs de la requete construite précédemments
$nameChamp = array_keys($tabTemp[0]);

// Ci-dessous on applique le tableau de titres $nameChamp au fichier excel pour créer les titres de chaque colonne
$sheet = $spreadsheet->getActiveSheet();
$sheet->fromArray($nameChamp,NULL,'A1');

// Boucle pour créer tous le fichier excel
// On parcours le fichier excel et on y implémente les données de la requete SQL.
// Quand il n'y a plus de données, la boucle s'arrète
$x = 1;
$query1->execute();
while($row = $query1->fetch(PDO::FETCH_OBJ)) {
    $sheetArray = array();
    ++$x;
    foreach ($nameChamp as $key => $value) {
        array_push($sheetArray, $row->$value);
    }
    $sheet->fromArray($sheetArray,NULL,'A'.$x);
} // Fin de la boucle while 

// Le tableau styleArrayBorderExt définit les bordure extérieures du tableau excel et des titres
$styleArrayBorderExt = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
]; // Fin du tableau $styleArrayBorderExt

// Le tableau styleArrayBorderInt définit les bordures intérieur du tableau excel et des titres
$styleArrayBorderInt = [
    'borders' => [
        'inside' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF778899'],
        ],
    ],
]; // Fin du tableau $styleArrayBorderInt

// Le tableau styleArrayAlign définit les méthode d'alignement du texte dans le tableau
$styleArrayAlign = [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'textRotation' => 0,
    'wrapText' => FALSE,
]; // Fin du tableau $styleArrayAlign

// Le tableau $styleArrayFontTitle définit la police, la taille et le caractère gras des titres
$styleArrayFontTitle = [
    'name' => 'Arial',
    'bold' => TRUE,
    'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE,
    'size' => 13,
    'strikethrough' => FALSE,
    'color' => ['argb' => 'FF000000'],
]; // Fin du tableau $styleArrayFontTitle

// Le tableau $styleArrayFontData définit la police, la taille et le caractère gras des données
$styleArrayFontData = [
    'name' => 'Arial',
    'bold' => FALSE,
    'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE,
    'size' => 11,
    'strikethrough' => FALSE,
    'color' => ['argb' => 'FF000000'],
]; // Fin du tableau $styleArrayFontData

// Dans le code qui suit on applique toutes les mise en forme créer précédemments via des tableaux
// Application des bordures extérieurs
// Pour les données
$sheet->getStyle('A1:'.$alphaArray[count($nameChamp)].$x)->applyFromArray($styleArrayBorderExt);
// Pour les titres
$sheet->getStyle('A1:'.$alphaArray[count($nameChamp)].'1')->applyFromArray($styleArrayBorderExt);
// Application des bordures intérieurs du tableau
// Pour les données
$sheet->getStyle('A2:'.$alphaArray[count($nameChamp)].$x)->applyFromArray($styleArrayBorderInt);
// Pour les titres
$sheet->getStyle('A1:'.$alphaArray[count($nameChamp)]."1")->applyFromArray($styleArrayBorderInt);
// Application de l'alignement du texte
$sheet->getStyle('A1:'.$alphaArray[count($nameChamp)].$x)->getAlignment()->applyFromArray($styleArrayAlign);
// Application des paramètres pour la police des titres
$sheet->getStyle('A1:'.$alphaArray[count($nameChamp)].'1')->getFont()->applyFromArray($styleArrayFontTitle);
// Application des paramètres pour la police des données
$sheet->getStyle('A2:'.$alphaArray[count($nameChamp)].$x)->getFont()->applyFromArray($styleArrayFontData);

// Boucle pour ajuster automatique la largeur des colonnes utilisés
for ($i=1; $i <= count($nameChamp); $i++) {
    $sheet->getColumnDimension($alphaArray[$i])->setAutoSize(true);
}

// Enfin, on écrit le fichier excel quand tous les paramètres sont appliqués
$writer = new Xlsx($spreadsheet);
// On sauvegarde le fichier excel avec un nom composé de la date du jour et du mot extract
// On précise le chemin de sauvegarde du fichier dans le même temps
$writer->save(date('d_m_y').'_Extract.xlsx');
?>