<?php

namespace IlBronza\Products\Tests\Unit\Helpers\Costs;

use IlBronza\Products\Helpers\Costs\VatCalculatorCostsHelper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class VatCalculatorCostsHelperTest extends TestCase
{
	public function test_it_calculates_every_value_from_each_pair_of_the_other_values() : void
	{
		$taxable_amount = 100.0;
		$vat_percentage = 22.0;
		$vat_amount = 22.0;
		$vatted_amount = 122.0;

		$this->assertSame($vat_percentage, VatCalculatorCostsHelper::calculateVatPercentageFromTaxableAmountAndVatAmount($taxable_amount, $vat_amount));
		$this->assertSame($vat_percentage, VatCalculatorCostsHelper::calculateVatPercentageFromTaxableAmountAndVattedAmount($taxable_amount, $vatted_amount));
		$this->assertSame($vat_percentage, VatCalculatorCostsHelper::calculateVatPercentageFromVatAmountAndVattedAmount($vat_amount, $vatted_amount));

		$this->assertSame($taxable_amount, VatCalculatorCostsHelper::calculateTaxableAmountFromVatPercentageAndVatAmount($vat_percentage, $vat_amount));
		$this->assertSame($taxable_amount, VatCalculatorCostsHelper::calculateTaxableAmountFromVatPercentageAndVattedAmount($vat_percentage, $vatted_amount));
		$this->assertSame($taxable_amount, VatCalculatorCostsHelper::calculateTaxableAmountFromVatAmountAndVattedAmount($vat_amount, $vatted_amount));

		$this->assertSame($vat_amount, VatCalculatorCostsHelper::calculateVatAmountFromTaxableAmountAndVatPercentage($taxable_amount, $vat_percentage));
		$this->assertSame($vat_amount, VatCalculatorCostsHelper::calculateVatAmountFromTaxableAmountAndVattedAmount($taxable_amount, $vatted_amount));
		$this->assertSame($vat_amount, VatCalculatorCostsHelper::calculateVatAmountFromVatPercentageAndVattedAmount($vat_percentage, $vatted_amount));

		$this->assertSame($vatted_amount, VatCalculatorCostsHelper::calculateVattedAmountFromTaxableAmountAndVatPercentage($taxable_amount, $vat_percentage));
		$this->assertSame($vatted_amount, VatCalculatorCostsHelper::calculateVattedAmountFromTaxableAmountAndVatAmount($taxable_amount, $vat_amount));
		$this->assertSame($vatted_amount, VatCalculatorCostsHelper::calculateVattedAmountFromVatPercentageAndVatAmount($vat_percentage, $vat_amount));
	}

	public function test_it_rejects_indeterminate_calculations() : void
	{
		$this->expectException(InvalidArgumentException::class);

		VatCalculatorCostsHelper::calculateVatPercentageFromTaxableAmountAndVatAmount(0.0, 0.0);
	}
}
