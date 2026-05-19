<?php

namespace IlBronza\Products\Http\Controllers\Project;

use IlBronza\CRUD\Traits\CRUDNestableTrait;
use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\Quotations\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProjectReorderController extends ProjectCRUD
{
    public bool $replaceName = true;

    use CRUDNestableTrait {
        getSortableElementsTree as protected traitGetSortableElementsTree;
        getSortableElements as protected traitGetSortableElements;
    }

    protected ?Client $client = null;

    /**
     * http methods allowed. remove non existing methods to get a 403
     **/
    public $allowedMethods = [
        'clients',
        'reorderByClient',
        'reorder',
        'storeReorder'
    ];

    public function getNestableElementViewName()
    {
        return 'products::nestable.production';
    }

    public function clients(Request $request)
    {
        $clients = Client::gpc()::query()
            ->has('projects')
            ->withCount('projects')
            ->orderBy('name')
            ->get();

        return view('products::projects.reorder.clients', [
            'clients' => $clients
        ]);
    }

    public function getSortableElements($modelInstance) : Collection
    {
        //usare modelinstance per avere i suoi figli (per coerenza)
        return $this->getModelClass()::byClient($this->client)->get();
    }

    public function reorderByClient(Request $request, $client)
    {
        $this->client = Client::gpc()::findOrFail($client);

        view()->share('pageTitle', 'Riordina le produzioni di ' . $this->client->getName());

        return $this->_reorder($request, null);
    }
}