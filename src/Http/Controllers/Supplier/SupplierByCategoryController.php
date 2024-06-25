<?php

namespace IlBronza\Products\Http\Controllers\Supplier;

use IlBronza\Category\Models\Category;
use IlBronza\Products\Models\Sellables\Supplier;
use Illuminate\Http\Request;

class SupplierByCategoryController extends SupplierIndexController
{
    public $allowedMethods = ['byCategory'];

    public function byCategory(Request $request, string $category)
    {
        $this->category = Category::find($category);

        return $this->_index($request);
    }

    public function getIndexElements()
    {
        return Supplier::getProjectClassname()::whereHas('categories', function($query)
        {
            $query->where($this->category->getTable() . '.id', $this->category->getKey());
        })->with(
            'categories',
            'target.destinations.address',
            'sellables'
        )
        ->withCount('quotationrows')
        ->get();
    }

}
