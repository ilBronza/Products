<?php

namespace IlBronza\Products\Providers\Helpers\Permissions;

class EconomicsPermissionsHelper
{
	public static function roleName() : ?string
	{
		return config('products.roles.economics');
	}

	public static function onlyRoles(array $fieldNames) : array
	{
		if (! $role = static::roleName())
			return [];

		$onlyRoles = [];

		foreach ($fieldNames as $fieldName)
			$onlyRoles[$fieldName] = ['roles' => [$role]];

		return $onlyRoles;
	}

	public static function mergeInto(array $fieldsGroup, array $fieldNames) : array
	{
		$existingFields = array_keys($fieldsGroup['fields'] ?? []);
		$fieldNames = array_values(array_intersect($fieldNames, $existingFields));

		if (count($fieldNames) === 0)
			return static::pruneOnlyRoles($fieldsGroup);

		$fieldsGroup['permissions']['onlyRoles'] = array_merge(
			$fieldsGroup['permissions']['onlyRoles'] ?? [],
			static::onlyRoles($fieldNames)
		);

		return static::pruneOnlyRoles($fieldsGroup);
	}

	public static function pruneOnlyRoles(array $fieldsGroup) : array
	{
		if (! isset($fieldsGroup['permissions']['onlyRoles']))
			return $fieldsGroup;

		$existingFields = array_keys($fieldsGroup['fields'] ?? []);

		foreach (array_keys($fieldsGroup['permissions']['onlyRoles']) as $permissionField)
			if (! in_array($permissionField, $existingFields, true))
				unset($fieldsGroup['permissions']['onlyRoles'][$permissionField]);

		if (count($fieldsGroup['permissions']['onlyRoles']) === 0)
			unset($fieldsGroup['permissions']['onlyRoles']);

		if (isset($fieldsGroup['permissions']) && count($fieldsGroup['permissions']) === 0)
			unset($fieldsGroup['permissions']);

		return $fieldsGroup;
	}
}
