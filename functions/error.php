<?php
require_once __DIR__ . '/../classes/CategoryError.php';
require_once __DIR__ . '/../classes/ProductError.php';

function categoryErrorMessage(int $errorCode): string
{
    return match ($errorCode) {
        CategoryError::NAME_REQUIRED => "Le nom est obligatoire",
        default => "Une erreur est survenue"
    };
}

function productErrorMessage(int $errorCode): string
{
    return match ($errorCode) {
        ProductError::NAME_REQUIRED => "Le nom est obligatoire",
        ProductError::PRICE_REQUIRED => "Le prix est obligatoire",
        ProductError::COVER_REQUIRED => "Une image est obligatoire",
        ProductError::DESCRIPTION_REQUIRED => "La description est obligatoire",
        ProductError::CATEGORIE_REQUIRED => "La catégorie est obligatoire",
        default => "Une erreur est survenue"
    };
}