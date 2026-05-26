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
		'awaiting' => 'In attesa',
		'show' => 'Mostra ordine :model',
		'edit' => ':model',
		'index' => 'Indice commesse',
		'current' => 'Commesse attive',
		'addOrderrowsByTable' => 'Aggiungi servizi/beni',
		'active' => 'Commesse attive',
		'all' => 'Tutte Commesse',
		'addSellableSupplierRows' => 'Aggiungi riga specifica'
	],

	'ibProductsproducts' => [
		'index' => 'Lista Prodotti',
		'current' => 'Recenti',
	],

	'ibProductssellableSuppliers' => [
		'index' => 'Beni/Servizi per fornitore',
	],

	'ibProductssellables' => [
		'index' => 'Indice beni/servizi disponibili',
		'byType' => 'Indice beni/servizi disponibili per tipo: :type'
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
		'index' => 'Lista accessori',
		'create' => 'Crea accessorio',
		'createByParent' => 'Crea accessorio figlio',
		'show' => 'Mostra accessorio :model',
		'edit' => 'Modifica accessorio :model',
	],

	'ibProductsaccessoryTypes' => [
		'index' => 'Lista tipi accessorio',
		'create' => 'Crea tipo accessorio',
		'show' => 'Mostra tipo accessorio :model',
		'edit' => 'Modifica tipo accessorio :model',
	],

	'accessories' => [
		'index' => 'Lista accessori',
		'create' => 'Crea accessorio',
		'createByParent' => 'Crea accessorio figlio',
		'show' => 'Mostra accessorio :model',
		'edit' => 'Modifica accessorio :model',
	],

	'accessoryTypes' => [
		'index' => 'Lista tipi accessorio',
		'create' => 'Crea tipo accessorio',
		'show' => 'Mostra tipo accessorio :model',
		'edit' => 'Modifica tipo accessorio :model',
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
