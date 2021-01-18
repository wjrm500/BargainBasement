<?php

use app\models\Permission;
use app\models\User;
use app\models\UserPermission;

require_once dirname(__DIR__) . '/bootstrap.php';

$permissionsToAdd = [
    ['name' => 'Admin Permissions', 'href' => '/admin/permission'],
    ['name' => 'Customer Feedback', 'href' => '/admin/customer'],
    ['name' => 'Product Categories', 'href' => '/admin/product-category'],
    ['name' => 'Products', 'href' => '/admin/product'],
    ['name' => 'Promos', 'href' => '/admin/promo'],
    ['name' => 'Special Offers', 'href' => '/admin/special-offer']
];
$permission = new Permission();
$superAdminUserId = User::find(['username' => 'wjrm500@gmail.com'])->id;
$userPermission = new UserPermission();

foreach ($permissionsToAdd as $permissionToAdd) {
    $permissionId = $permission->bindAndSave([
        'name' => $permissionToAdd['name'],
        'href' => $permissionToAdd['href'],
    ], true);
    $adminUserPermission->bindAndSave([
        'user_id'       => $superAdminUserId,
        'permission_id' => $permissionId
    ]);
}