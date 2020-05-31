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
// Il faut modifier la chaine pour y entrer les données de connexion de la BDD en dur
try {
    $pdo = new PDO('mysql:host=localhost;dbname=16_09_19_php01',"root","") or die ("Attention, problème de connexion serveur");	
} catch (PDOExceptions $e) {
    echo "Impossible de se connecter a la base de données, abandon de la procédure.";
}
echo "Connexion a la BDD réussi";
echo "<br>";

// Création d'une chaine de caractère qui représente la requete SQL des données voulues
$sql01 = "SELECT nom as Nom, prenom as Prenom, classe as Classe, id_etudiant as Identifiants FROM etudiants;";

// Préparation des requetes
$query1 = $pdo->query($sql01);
$tabTemp = $query1->fetchAll(PDO::FETCH_ASSOC);

// Création du fichier excel
$spreadsheet = new Spreadsheet();

// Création d'un tableau pour les titres du tableau excel
// Ce tableau reprend les index du fetch construit précédemment
$nameChamp = array_keys($tabTemp[0]);

foreach ($nameChamp as $key => $value) {
    echo strlen($value)."<br>";
}

// Ci-dessous on applique le tableau de titres $nameChamp au fichier excel pour créer les titres de chaque colonne
$sheet = $spreadsheet->getActiveSheet();
$sheet->fromArray($nameChamp,NULL,'A1');

// Boucle pour créer tous le fichier excel
// On complete le reste du tableau excel via des plus petit tableau.
$x = 1;
$query1->execute();
while($row = $query1->fetch(PDO::FETCH_OBJ)) {
    $sheetArray = array();
    ++$x;
    echo $x."<br>";
    foreach ($nameChamp as $key => $value) {
        array_push($sheetArray, $row->$value);
    }
    $sheet->fromArray($sheetArray,NULL,'A'.$x);
    var_dump($sheetArray); // TEST --------------------------------------------------------------------------->
    //$sheet->setCellValue('A'.$x, $row->nom);
} // fin de while 

// Le tableau styleArrayBorderExt définit les bordure extérieures du tableau excel et des titres
$styleArrayBorderExt = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];

// Le tableau styleArrayBorderInt définit les bordures intérieur du tableau excel
$styleArrayBorderInt = [
    'borders' => [
        'inside' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF778899'],
        ],
    ],
];

// Le tableau styleArrayAlign définit les méthode d'alignement du texte dans le tableau
$styleArrayAlign = [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'textRotation' => 0,
    'wrapText' => FALSE,
];

// Le tableau $styleArrayFont définit la police ainsi que sa taille, les caractères en gras et surligné
$styleArrayFontTitle = [
    'name' => 'Arial',
    'bold' => TRUE,
    'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE,
    'size' => 13,
    'strikethrough' => FALSE,
    'color' => ['argb' => 'FF000000'],
];

$styleArrayFontData = [
    'name' => 'Arial',
    'bold' => FALSE,
    'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE,
    'size' => 11,
    'strikethrough' => FALSE,
    'color' => ['argb' => 'FF000000'],
];

// Dans le code qui suit on applique toutes les mise en forme créer précédemments
// Application de la bordure extérieur du tableaux excel et des titres
$sheet->getStyle('A1:'.$alphaArray[count($nameChamp)].$x)->applyFromArray($styleArrayBorderExt);
$sheet->getStyle('A1:'.$alphaArray[count($nameChamp)].'1')->applyFromArray($styleArrayBorderExt);
// Application de la bordure intérieur du tableau de données et des titres
$sheet->getStyle('A2:'.$alphaArray[count($nameChamp)].$x)->applyFromArray($styleArrayBorderInt);
$sheet->getStyle('A1:'.$alphaArray[count($nameChamp)]."1")->applyFromArray($styleArrayBorderInt);
// Application de l'alignement du texte
$sheet->getStyle('A1:'.$alphaArray[count($nameChamp)].$x)->getAlignment()->applyFromArray($styleArrayAlign);
// Application de la police des titres
$sheet->getStyle('A1:'.$alphaArray[count($nameChamp)].'1')->getFont()->applyFromArray($styleArrayFontTitle);
// Application de la police des données
$sheet->getStyle('A2:'.$alphaArray[count($nameChamp)].$x)->getFont()->applyFromArray($styleArrayFontData);

// Boucle pour set la largeur de chaque colonne automatiquement a la plus grande longueur de texte de la colonne
for ($i=1; $i <= count($nameChamp); $i++) {
    // Application d'une taille de colonne
    $sheet->getColumnDimension($alphaArray[$i])->setAutoSize(true);
}

// On écrit le fichier avec la variable instancier au dessus, 
// pour que le fichier de script se différencie des autres on lui ajoute la date du jour de création
$writer = new Xlsx($spreadsheet);
$writer->save(date('d_m_y').'_Extract.xlsx');
?>