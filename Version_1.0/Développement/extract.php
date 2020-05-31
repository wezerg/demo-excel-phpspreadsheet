<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
        // On viens chercher le fichier autolad.php
        require __DIR__ . '/vendor/autoload.php';
        require __DIR__ . '/function.php';
        // Importation des classes de création de fichier
        /*
        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

        // Création du fichier, on met dans la cellule A1 : Hello World !
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        // On écrit le fichier avec la variable instancier au dessus
        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');
        */
        /* classe de connexion Composer/monolog
        // On créer un login
        $log = new Monolog\Logger('name');
        // Ici on fait appel au classe de composer pour créer un fichier app.log et enregistrer toute les connexion aux fichiers
        // Connexion possible au id de la BDD UPTO ? 
        $log->pushHandler(new Monolog\Handler\StreamHandler('app.log', Monolog\Logger::WARNING));
        // Ci-dessous le nom envoyé dans le fichier app.log
        $log->addWarning('Foo');
        */
        
    ?>
    <h1>La page d'extract</h1>
    <form method="post" action="">
        <p>
            Serveur : 
            <input type = text name='server' />
            Base de données :  
            <input type = text name='BDD' />
            Login : 
            <input type = text name='login' />
            Mot de passe : 
            <input type = text name='mdp' />
            <input type = submit value='OK' name="valider" />
        </p>
    </form>
    <?php
    if (isset($_POST['valider'])) {
        if (!empty($_POST['server']) && !empty($_POST['BDD']) && !empty($_POST['login']) && !empty($_POST['mdp'])) {
            try {
                $pdo = connexion(($_POST['server']),($_POST['BDD']),($_POST['login']),($_POST['mdp']));
                echo "Connexion réussi";
            } catch (PDOExceptions $e) {
                echo "Une erreur s'est produite lors de la connexion à la base de donnée";
            }  
        }
        else {
            echo "Aucune données de connexion entrée";
        }
    }
    ?>
    <br/>
    <form method="post" action="">
       
        <p>
            Projet : <input type = text name='Projet' />
        </p>
        <p>
            Etude de cas : <input type = text name='Etude'/>
        </p>
        <p>
            Parcours professionnel : <input type = text name='Parcours'/>
        </p>
        <p>
            <input type = submit value= OK />
        </p>
        
    </form>
    <?php
    
    ?>
</body>
</html>