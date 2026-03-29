<?php

namespace IlBronza\Products\Http\Middleware;

use IlBronza\CRUD\Middleware\CRUDBasePackageMiddlewareRolesPermissions;

/**
 * Resolves allowed roles for Products routes from config (products.defaultRoles / products.routeRoles).
 */
class ProductsMiddlewareRolesPermissions extends CRUDBasePackageMiddlewareRolesPermissions
{
    protected string $configPackageName = 'products';
}
