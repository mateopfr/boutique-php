<?php
require_once __DIR__ . '/classes/Products.php';
require_once __DIR__ . '/layout/header.php';
?>

<?php
$productsDb = new Products();
$products = $productsDb->findAll();
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de Produits</title>
    <!-- Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
    <div class="max-w-2xl bg-white p-8 rounded shadow-md">
        <h1 class="text-2xl font-bold mb-4">Liste de Produits</h1>

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Nom</th>
                    <th class="py-2 px-4 border">Prix</th>
                    <th class="py-2 px-4 border">Image</th>
                    <th class="py-2 px-4 border">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr class="border">
                        <td class="py-2 px-4 border"><?php echo $product['id'] ; ?></td>
                        <td class="py-2 px-4 border"><?php echo $product['name'] ; ?></td>
                        <td class="py-2 px-4 border"><?php echo '$' . number_format($product['price_vat_free'], 2); ?></td>
                        <td class="py-2 px-4 border"><img src="/img/<?php echo $product['cover']; ?>" alt="<?php echo $product['name']; ?>" class="w-16 h-16 object-cover"></td>
                        <td class="py-2 px-4 border"><?php echo $product['description']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


<?php require_once __DIR__ . '/layout/footer.php'; ?>