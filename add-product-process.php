<?php
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/functions/utils.php';
require_once __DIR__ . '/classes/ProductError.php';

/**
 * Génère une chaine de caratère en fonction du paramètre length
 *
 * @param int $length Taille de la chaine à générer
 */
function rand_char($length) {
    $random = '';
    for ($i = 0; $i < $length; $i++) {
      $random .= chr(mt_rand(33, 126));
    }
    return $random;
  }

/**
 * Vérifie l'existence du nom de l'image dans la base
 *
 * @param string $randomStr Nom de l'image
 * @param PDO $pdo php data object (la bdd)
 */
function isStrExiste($image, $pdo) {
    $image = preg_replace('/[\'\"]/', '', $image);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM product WHERE cover = ?");
    $stmt->execute([$image]);
    return $stmt->fetchColumn() > 0;
}

// Check que $_FILES ne soit pas vide
if (isset($_FILES['myFile'])) {
    $file = $_FILES['myFile'];

    // Exceptionnellement, on va tester directement ici pour l'image.
    // TODO: (L'ordre des erreurs va donc différer de celui des champs HTML, à voir si ce ne n'est pas possible de le gérer plus loin...)
    $filename = $file['name'];
    if (empty($filename)) {
        redirect('/add-product.php?error=' . ProductError::COVER_REQUIRED);
    }

    $newFileName = null;
    try {
        // Connection à la base
        $pdo = Database::getConnection();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Tant que le nom de l'image existe dans la base, on génère une nouvelle chaine random
        $randomStr = "";
        do {
            $randomStr = rand_char(12);
        } while (isStrExiste($randomStr, $pdo));    

        // On récupère l'extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        // Vérification de l'extension du fichier
        if ($ext !== 'jpg' && $ext !== 'jpeg' && $ext !== 'png' && $ext !== 'webp') {
            echo $ext;
            redirect('/add-product.php?error=' . ProductError::COVER_REQUIRED);
        }

        // Création du chemin pour l'image, et enregistrement à cet endroit là
        if($randomStr != null) {
            $newFileName = $randomStr . "." . $ext;
            $destination = __DIR__ . "/img/" . $newFileName;
            move_uploaded_file($file['tmp_name'], $destination);
        }
    } catch (PDOException $e) {
        // TODO : C'est + une erreur bdd qu'une erreur concernant l'image
        // Faire une classe pour les erreurs en général pour gérer les cas comme ici
        redirect('/add-product.php?error=' . ProductError::COVER_REQUIRED);
    }

  }

  // Gestion erreur des champs / affectation
if (!isset($_POST['name']) || !isset($_POST['price']) || !isset($randomStr) || !isset($_POST['description']) || !isset($_POST['category'])) {
    redirect('/');
}

$productName = trim($_POST['name']);
$productPrice = trim($_POST['price']);
$productCover = $randomStr;
$productDescription = trim($_POST['description']);
$productCategory = $_POST['category'];

if (empty($productName)) {
    redirect('/add-product.php?error=' . ProductError::NAME_REQUIRED);
}
if (empty($productPrice)) { 
    redirect('/add-product.php?error=' . ProductError::PRICE_REQUIRED);
}
if (empty($randomStr)) {
    redirect('/add-product.php?error=' . ProductError::COVER_REQUIRED);
}
if (empty($productDescription)) {
    redirect('/add-product.php?error=' . ProductError::DESCRIPTION_REQUIRED);
}
if (empty($productCategory)) {
    redirect('/add-product.php?error=' . ProductError::CATEGORIE_REQUIRED);
}

try {
    $pdo = Database::getConnection();
} catch(PDOException $ex) {
    echo "Erreur lors de la connexion à la base de données";
    exit;
}

$stmt = $pdo->prepare("INSERT INTO product (name, price_vat_free, cover, description, category_id) VALUES (:productName, :productPrice, :productCover, :productDescription, :productCategory)");
$stmt->execute([
    'productName' => $productName,
    'productPrice' => $productPrice,
    'productCover' => $productCover,
    'productDescription' => $productDescription,
    'productCategory' => $productCategory
]);

if ($stmt === false) {
    echo "Erreur lors de la requête";
    exit;
}

session_start();
$_SESSION['message'] = "Le produit a bien été enregistré";
redirect('/');