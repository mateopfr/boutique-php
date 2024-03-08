<?php
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/functions/utils.php';
require_once __DIR__ . '/classes/ProductError.php';
// $pdo, $coverName

function rand_char($length) {
    $random = '';
    for ($i = 0; $i < $length; $i++) {
      $random .= chr(mt_rand(33, 126));
    }
    return $random;
  }

function isStrExists($randomStr, $pdo) {
    $randomStr = preg_replace('/[\'\"]/', '', $randomStr);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM product WHERE cover = ?");
    $stmt->execute([$randomStr]);
    return $stmt->fetchColumn() > 0;
}

if (isset($_FILES['myFile'])) {
    $file = $_FILES['myFile'];

    $filename = $file['name'];

    $newFileName = null;
    try {
        $pdo = Database::getConnection();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        do {
            $randomStr = rand_char(12);
        } while (isStrExists($randomStr, $pdo));
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $newFileName = $randomstr . "." . $ext;
        $destination = __DIR__ . "/img/" . $newFileName;
        move_uploaded_file($file['tmp_name'], $destination);
    } catch (PDOException $e) {
        redirect('/add-product.php?error=' . ProductError::COVER_REQUIRED);
    }

  }

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
if (empty($productCover)) {
    redirect('/add-product.php?error=' . ProductError::COVER_REQUIRED);
}
if (empty($productDescription)) {
    redirect('/add-product.php?error=' . ProductError::DESCRIPTION_REQUIRED);
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

// redirect('/');