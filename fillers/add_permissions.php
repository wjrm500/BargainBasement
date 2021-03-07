<?php

use app\models\Permission;
use app\models\User;
use app\models\UserPermission;

require_once dirname(__DIR__) . '/bootstrap.php';

$permissionsToAdd = [
    ['name' => 'Admin Permissions', 'item_name' => 'Admin Permission', 'href' => '/admin/permission'],
    ['name' => 'Customer Feedback', 'item_name' => 'Customer Feedback Form', 'href' => '/admin/customer'],
    ['name' => 'Product Categories', 'item_name' => 'Product Category', 'href' => '/admin/product-category'],
    ['name' => 'Products', 'item_name' => 'Product', 'href' => '/admin/product'],
    ['name' => 'Promos', 'item_name' => 'Promotion', 'href' => '/admin/promo'],
    ['name' => 'Special Offers', 'item_name' => 'Special Offer', 'href' => '/admin/special-offer']
];
$permission = new Permission();
$superAdminUserId = User::find(['username' => 'wjrm500@gmail.com'])->id;
$userPermission = new UserPermission();

foreach ($permissionsToAdd as $permissionToAdd) {
    $permissionId = $permission->bindSave([
        'name'      => $permissionToAdd['name'],
        'item_name' => $permissionToAdd['item_name'],
        'href'      => $permissionToAdd['href'],
    ], true);
    $userPermission->bindSave([
        'user_id'       => $superAdminUserId,
        'permission_id' => $permissionId
    ]);
}