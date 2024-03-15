
<center>
    <h1 align="center">php shop</h1>
    <h4 align="center">Boutique en ligne réalisée avec <strong>php</strong> </h4>
    <p align="center">
        <strong>Dernière mise à jour :</strong> 15 mars 2024<br>
    </p> 
</center>

### Features :
* Possibilité à l'utilisateur d'upload une image pour la cover d'un produit
* Génération d'un nom de fichier random, et vérification de ce nom pour éviter les doublons
    * Chaine de 12 caractères ASCII (33~126) aléatoire. (18 891 992 292 798 775 135 584 possibilités)
* Vérification du format de l'image. (jpg/jpeg/png/webp)
* Gestion des erreurs pour les produits.
* Affichage des produits avec Tailwind.

#### Majorité des fichiers concernés par ses changements :
- add-product.php
- add-product-process.php
- index.php
- ProductError.php
- Products.php
- functions/error.php



### Installation :
* Télécharger le [repo'](https://github.com/mateopfr/boutique-php/tree/master)
* Télécharger [WampServer](https://www.wampserver.com/en/)
* Importer php_store.sql dans phpMyAdmin pour avoir la base et les tables correspondantes à notre code.
* Faire correspondre les paramètres de la BDD avec config/db.ini.

#### Lancer le serveur PHP

```powershell
php -S localhost:8000
```




