<?php

use app\models\Country;

require_once dirname(__DIR__) . '/bootstrap.php';

$countriesToAdd = [
    'England',
    'Northern Ireland',
    'Scotland',
    'Wales'
];

$countryModel = new Country();
foreach ($countriesToAdd as $countryToAdd) {
    $countryModel->bindData(['name' => $countryToAdd]);
    $countryModel->save();
}