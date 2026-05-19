<?php

namespace IlBronza\Products\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait CURSORRowsHelper
{
	/**
	 * Generic helper to retrieve rows by a "type name" (e.g. productRows) and group them
	 * by a field on the row (e.g. people_coefficient).
	 *
	 * Example: $this->getGroupedRowsByTypeField('productRows', 'people_coefficient')
	 */
	public function getGroupedRowsByTypeField(string $type, string $field): Collection
	{
		$method = 'get' . Str::studly($type);

		$rows = method_exists($this, $method) ? $this->{$method}() : collect();

		if (! ($rows instanceof Collection)) {
			$rows = collect($rows);
		}

		$rows = $rows->sortBy(fn($row) => $row->sorting_index ?? 999);

		$grouped = $rows->groupBy(function ($row) use ($field) {
			$value = data_get($row, $field);
			$value = is_string($value) ? trim($value) : $value;

			if ($value === null || $value === '' || $value === false) {
				return 'base';
			}

			return (string) $value;
		});

		return $grouped
			->sortKeysUsing(function ($a, $b) {
				if ($a === 'base' && $b !== 'base') {
					return -1;
				}
				if ($b === 'base' && $a !== 'base') {
					return 1;
				}
				return strcmp((string) $a, (string) $b);
			});
	}
}

