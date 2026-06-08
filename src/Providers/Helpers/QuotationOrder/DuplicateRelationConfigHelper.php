<?php

namespace IlBronza\Products\Providers\Helpers\QuotationOrder;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class DuplicateRelationConfigHelper
{
	public static function isMultipleFromRelation(Relation $relation) : bool
	{
		return ! (($relation instanceof HasOne) || ($relation instanceof BelongsTo) || ($relation instanceof MorphOne));
	}

	/**
	 * @param bool|null $explicit Null = infer from relation type
	 */
	public static function resolveMultiple(Relation $relation, ?bool $explicit) : bool
	{
		if ($explicit !== null)
			return $explicit;

		return static::isMultipleFromRelation($relation);
	}

	/** @param mixed $config */
	protected static function isActiveDuplicateRelationsEntry($config) : bool
	{
		return $config !== false && is_array($config);
	}

	/**
	 * Drops disabled entries (literal false) so they do not appear in the UI nor in duplicate logic.
	 *
	 * @param array<string, mixed> $duplicateRelationsRaw
	 * @return array<string, array<mixed>>
	 */
	public static function normalizedDuplicateRelationsMap(array $duplicateRelationsRaw) : array
	{
		$out = [];

		foreach ($duplicateRelationsRaw as $key => $config)
			if (static::isActiveDuplicateRelationsEntry($config))
				$out[(string) $key] = $config;

		return $out;
	}

	/**
	 * Colonne da non copiare in duplicazione righe (handler rows).
	 * Unisce elenco standard di config con override per relazione.
	 *
	 * @param  array<string, mixed>  $relationConfig
	 * @return array{row: list<string>, extraFields: list<string>}
	 */
	public static function resolveDuplicateRowExcludedAttributes(array $relationConfig) : array
	{
		$row = [];
		$extraFields = [];

		if ($relationConfig['useStandardExcludedAttributes'] ?? false)
		{
			$standard = config('products.duplicateRowStandardExcludedAttributes', []);

			if (is_array($standard))
			{
				$row = array_merge($row, $standard['row'] ?? []);
				$extraFields = array_merge($extraFields, $standard['extraFields'] ?? []);
			}
		}

		$row = array_merge($row, $relationConfig['excludeRowAttributes'] ?? []);
		$extraFields = array_merge($extraFields, $relationConfig['excludeExtraFieldsAttributes'] ?? []);

		return [
			'row' => array_values(array_unique(array_map('strval', $row))),
			'extraFields' => array_values(array_unique(array_map('strval', $extraFields))),
		];
	}

	/** Suffix for trans("products::fields.{suffix}") — column header in duplicate UI. */
	public static function inferColumnLangKeySuffix(array $column) : string
	{
		if (! empty($column['attribute']))
			return (string) $column['attribute'];

		if (empty($column['method']))
			return '';

		$stripped = preg_replace('/^(get|is|has)/i', '', (string) $column['method']);

		return Str::snake(lcfirst($stripped));
	}
}
