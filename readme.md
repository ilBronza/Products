# Products

## Suppliers Creation from Standard Models

This package now includes a helper feature that allows you to create **Suppliers** directly from existing Eloquent models.

### How it works

Any Eloquent model can be converted into a Supplier by invoking the SupplierBulkCreator.  
The system will automatically generate the corresponding Supplier entry, linking it to the source model through its class name and primary key.

### Example Usage

#### config/products.php
declare a bulk Creator helper for the project

```php
'supplier' => [
    'helpers' => [
	    'bulkCreator' => SupplierBulkCreator::class
	]
],
```

#### App/Providers/Helpers/Suppliers/SupplierBulkCreator.php
pick the models to be used as suppliers

```php
namespace App\Providers\Helpers\Suppliers;

use IlBronza\Clients\Models\Client;
use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Providers\Helpers\Suppliers\SupplierBulkCreator as IbSupplierBulkCreator;
use Illuminate\Support\Collection;

class SupplierBulkCreator extends IbSupplierBulkCreator
{
    public function getPossibleSuppliersTarget() : Collection
    {
        $operators = Operator::gpc()::all();
        $companiesAsSupplier = Client::gpc()::asSupplier()->get();
        $merged = $operators->merge($companiesAsSupplier);
        return $merged;
    }
}
```


### Notes
- If a Supplier for the given model already exists, the factory will return it instead of creating a duplicate.
- The Supplier inherits descriptive fields from the model whenever possible.
- This feature is intended to simplify the integration of existing domain models into the Products package.

## Sellables Creation from Standard Models

This package now includes a helper feature that allows you to create **Sellables** directly from existing Eloquent models.

### How it works

Any Eloquent model can be converted into a Sellable by invoking the SellableBulkCreator.  
The system will automatically generate the corresponding Sellable entry, linking it to the source model through its class name and primary key.

### Example Usage

#### config/products.php
declare a bulk Creator helper for the project

```php
'sellable' => [
    'helpers' => [
	    'bulkCreator' => SellableBulkCreator::class
	]
],
```

#### App/Providers/Helpers/Sellables/SellableBulkCreator.php
pick the models to be used as suppliers

```php

namespace App\Providers\Helpers\Sellables;

use IlBronza\Operators\Models\Contracttype;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Providers\Helpers\Suppliers\SellableBulkCreator as IbSellableBulkCreator;
use Illuminate\Support\Collection;

class SellableBulkCreator extends IbSellableBulkCreator
{   
    public function getPossibleSellablesTarget() : Collection
    {
        $contracttypes = Contracttype::gpc()::all();
        $products = Product::gpc()::all();
        $result = $products->merge($contracttypes);
        return $result;
    }
}
```


### Notes
- If a Sellable for the given model already exists, the factory will return it instead of creating a duplicate.
- The Sellable inherits descriptive fields from the model whenever possible.
- This feature is intended to simplify the integration of existing domain models into the Products package.


### Sellable prices creation from Target Model

call this route
```php
app('products')->route('products.calculatePrices')
```

it will execute ProductCalculatePricesController@calculatePrices

```php
class ProductCalculatePricesController extends ProductCRUD
{
    public $allowedMethods = ['calculatePrices'];

    public function calculatePrices()
    {
        $products = $this->getModelClass()::all();

        foreach($products as $product)
            cache()->remember(
                $product->cacheKey('calculatePricesByTarget'),
                3600,
                function() use ($product)
                {
                    return SellablePricesCreatorHelper::calculatePricesByTarget($product);
                }
            );

        return response()->json([
            'status' => 'ok'
        ]);
    }
}
```

SellablePricesCreatorHelper::calculatePricesByTarget takes the product, call $product->getPriceBaseAttributes() and
populate all sellable prices.

for example if you declare getPriceBaseAttributes like this:

```php
namespace App\Overrides\Models\Products;

class Product extends IbProduct implements WithPriceInterface
{
	public function getPriceBaseAttributes()
	{
		return [
			'company_cost' => $this->company_cost,
			'client_price' => $this->client_price,
		];
	}
}
```

product sellables will have a company_cost and a client_price



