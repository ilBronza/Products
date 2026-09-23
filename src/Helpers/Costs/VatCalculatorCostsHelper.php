<?php

namespace IlBronza\Products\Helpers\Costs;

use InvalidArgumentException;

/**
 * Calcola i valori IVA senza applicare arrotondamenti.
 *
 * Relazioni usate:
 * - vat_amount = taxable_amount * vat_percentage / 100
 * - vatted_amount = taxable_amount + vat_amount
 */
class VatCalculatorCostsHelper
{
	public static function calculateVatPercentageFromTaxableAmountAndVatAmount(
		float $taxable_amount,
		float $vat_amount
	) : float
	{
		static::ensureNonZero($taxable_amount, 'taxable_amount');

		return $vat_amount * 100 / $taxable_amount;
	}

	public static function calculateVatPercentageFromTaxableAmountAndVattedAmount(
		float $taxable_amount,
		float $vatted_amount
	) : float
	{
		static::ensureNonZero($taxable_amount, 'taxable_amount');

		return ($vatted_amount - $taxable_amount) * 100 / $taxable_amount;
	}

	public static function calculateVatPercentageFromVatAmountAndVattedAmount(
		float $vat_amount,
		float $vatted_amount
	) : float
	{
		$taxable_amount = $vatted_amount - $vat_amount;

		static::ensureNonZero($taxable_amount, 'vatted_amount - vat_amount');

		return $vat_amount * 100 / $taxable_amount;
	}

	public static function calculateTaxableAmountFromVatPercentageAndVatAmount(
		float $vat_percentage,
		float $vat_amount
	) : float
	{
		static::ensureNonZero($vat_percentage, 'vat_percentage');

		return $vat_amount * 100 / $vat_percentage;
	}

	public static function calculateTaxableAmountFromVatPercentageAndVattedAmount(
		float $vat_percentage,
		float $vatted_amount
	) : float
	{
		$percentage_factor = 100 + $vat_percentage;

		static::ensureNonZero($percentage_factor, '100 + vat_percentage');

		return $vatted_amount * 100 / $percentage_factor;
	}

	public static function calculateTaxableAmountFromVatAmountAndVattedAmount(
		float $vat_amount,
		float $vatted_amount
	) : float
	{
		return $vatted_amount - $vat_amount;
	}

	public static function calculateVatAmountFromTaxableAmountAndVatPercentage(
		float $taxable_amount,
		float $vat_percentage
	) : float
	{
		return $taxable_amount * $vat_percentage / 100;
	}

	public static function calculateVatAmountFromTaxableAmountAndVattedAmount(
		float $taxable_amount,
		float $vatted_amount
	) : float
	{
		return $vatted_amount - $taxable_amount;
	}

	public static function calculateVatAmountFromVatPercentageAndVattedAmount(
		float $vat_percentage,
		float $vatted_amount
	) : float
	{
		$percentage_factor = 100 + $vat_percentage;

		static::ensureNonZero($percentage_factor, '100 + vat_percentage');

		return $vatted_amount * $vat_percentage / $percentage_factor;
	}

	public static function calculateVattedAmountFromTaxableAmountAndVatPercentage(
		float $taxable_amount,
		float $vat_percentage
	) : float
	{
		return $taxable_amount * (100 + $vat_percentage) / 100;
	}

	public static function calculateVattedAmountFromTaxableAmountAndVatAmount(
		float $taxable_amount,
		float $vat_amount
	) : float
	{
		return $taxable_amount + $vat_amount;
	}

	public static function calculateVattedAmountFromVatPercentageAndVatAmount(
		float $vat_percentage,
		float $vat_amount
	) : float
	{
		static::ensureNonZero($vat_percentage, 'vat_percentage');

		return $vat_amount * (100 + $vat_percentage) / $vat_percentage;
	}

	protected static function ensureNonZero(float $value, string $field) : void
	{
		if($value === 0.0)
		{
			throw new InvalidArgumentException(sprintf('Il valore "%s" non può essere zero per questo calcolo.', $field));
		}
	}
}
