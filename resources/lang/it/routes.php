<?php

return [
	'ibProductsquotations' => [
		'edit' => 'Modifica preventivo :model',
		'current' => 'Preventivi in corso',
		'show' => 'Mostra preventivo :model',
		'index' => 'Indice preventivi',

		'addQuotationrowsByTable' => 'Aggiungi dettagli'
	],

	'ibProductsorderProductPhases' => [
		'byWorkstation' => [
			'elaborated' => 'Elaborati per postazione :model',
			'toElaborate' => 'Elabora | :model',
		]
	],

	'ibProductsorders' => [
		'active' => 'Commesse in produzione',
		'show' => 'Mostra ordine :model',
		'edit' => ':model',
		'index' => 'Indice commesse',
		'current' => 'Commesse attive',
		'addOrderrowsByTable' => 'Aggiungi servizi/beni',
		'active' => 'Commesse attive',
		'all' => 'Tutte Commesse',
	],

	'ibProductsproducts' => [
		'index' => 'Lista Prodotti',
		'current' => 'Recenti',
	],

	'ibProductssellableSuppliers' => [
		'index' => 'Beni/Servizi',
	],

	'ibProductsfinishings' =>[
		'index' => 'Finiture'
	],

	'ibProductsworkstations' => [
		'index' => 'Lista postazioni'
	],

	'ibProductsprojects' => [
		'index' => 'Indice progetti',
	],

	'ibProductsorderrows' => [
		'findOrAssociateSupplier' => 'Trova o associa fornitore',
		'assignSellableSupplier' => 'Assegna fornitore',
	],

	'ibProductsquotationrows' => [
		'findOrAssociateSupplier' => 'Trova o associa fornitore',
		'assignSellableSupplier' => 'Assegna fornitore',
	],

	'ibProductssuppliers' => [
		'orderrows' => [
			'index' => 'Righe per fornitore :model',
		]
	],

	'ibProducts' => [
		'clientsorderProducts' => [
			'index' => 'Ordini per cliente',
		],

		'clients' => [
			'products' => [
				'index' => 'Prodotti per cliente',
			],
			'orderProducts' => [
				'index' => 'Ordini per cliente',
			]
		],
	],

	'ibProductsaccessories' => [
		'index' => 'Lista Accessori'
	],

	'ibProductsproducts' => ['current' => 'Prodotti Attuali',],

	'orderProducts' => [
     'regulateProduction' => [
		'index' => 'Ordini Prodotti',
	 ],
    ],

	'ibProductsorderProducts' => [
     'byWorkstation' => [
		'elaborated' => 'Commesse Elaborate Per Centro',
	 ],
    ],

	'products' => [
     'withoutStencil' => 'Prodotto Senza Stencil',
    ],

	'ibProductsorderProductPhases' => [
     'byWorkstation' => [
		'toElaborate' => 'Ordini Da Elaborare',
	 ],
    ],
];
