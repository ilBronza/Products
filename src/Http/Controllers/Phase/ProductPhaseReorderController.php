<?php

namespace IlBronza\Products\Http\Controllers\Phase;

use IlBronza\CRUD\Traits\CRUDNestableTrait;
use IlBronza\Products\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductPhaseReorderController extends PhaseCRUD
{
    use CRUDNestableTrait;

    public $allowedMethods = [
        'reorder',
        'storeReorder'
    ];

    public function getStoreReoderUrl()
    {
        return $this->getRouteUrlByType('storeReorder', [
            'product' => $this->product
        ]);
    }

    public function getSortableElementsTree()
    {
        $return = collect();

        $element = $this->getModel();

        $element->childs = $this->getSortableElements($this->getModel())->sortBy('sequence');

        return $return->push($element);
    }

    public function getSortableElements($modelInstance) : Collection
    {
        return $modelInstance->getPhases();
    }

    public function getEditReorderUrl(array $parameters = null)
    {
        return null;
    }

    public function reorder(Request $request, Product $product)
    {
        $this->product = $product;

        return $this->_reorder($request, $product);
    }

    public function storeReorder(Request $request)
    {
        $elementId = $this->removeLeadingControlCharacter($request->element_id);
        $parentId = $this->removeLeadingControlCharacter($request->parent_id);

        if(($parentId == 0)||($parentId == "")||($parentId == null))
        {
            return [
                'success' => false,
                'message' => 'Impossibile eliminare una fase in questa schermata'
            ];
        }

        $item = $this->getModelClass()::findOrFail($elementId);

        $item->product_id = $parentId;
        $item->save();

        if ($request->filled('siblings')) {
            $siblings = json_decode($request->input('siblings'));

            foreach ($siblings as $index => $sibling)
            {
                $siblingId = $this->removeLeadingControlCharacter($sibling);

                $item = $this->getModelClass()::findOrFail($siblingId);
                $item->sequence = $index;
                $item->save();
            }
        }

        return [
            'success' => true,
            'message' => 'Spostato con successo'
        ];

    }

}
