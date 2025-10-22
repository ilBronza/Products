<?php

namespace IlBronza\Products\Http\Controllers\Order;

class OrderTimelineController extends OrderCRUD
{
	public $allowedMethods = ['timeline'];

	public function timeline($order)
	{
		return [
			'groups' => [
				[
					'id' => 'martinella',
					'name' => 'Martinella',
					'className' => 'martinella',
				],
				[
					'id' => 'surrigagno',
					'name' => 'Martinella',
					'className' => 'martinella',
				]
			],
			'items' => [
				[
					'id' => 1,
					'start' => '2025-10-10', 'end' => '2025-10-12T12:35:00',
					'group' => 'martinella',
					'content' => '1 UNO'
				],
				[
					'id' => 2,
					'start' => '2025-10-11', 'end' => '2025-10-11T12:35:00',
					'group' => 'martinella',
					'content' => '1 DUE'
				],
				[
					'id' => 3,
					'start' => '2025-10-12', 'end' => '2025-10-12T12:35:00',
					'group' => 'martinella',
					'content' => '1 TRE'
				],
				[
					'id' => 4,
					'start' => '2025-10-10', 'end' => '2025-10-10T12:35:00',
					'group' => 'surrigagno',
					'content' => '2 QUATTRO'
				],
				[
					'id' => 5,
					'start' => '2025-10-11', 'end' => '2025-10-11T12:35:00',
					'group' => 'surrigagno',
					'content' => '2 CINQUE'
				],
				[
					'id' => 6,
					'start' => '2025-10-12', 'end' => '2025-10-12T12:35:00',
					'group' => 'surrigagno',
					'content' => '2 SEI'
				], [
					'id' => 7,
					'start' => '2025-10-13', 'end' => '2025-10-13T12:35:00',
					'group' => 'surrigagno',
					'content' => '2 SETTE'
				]
				, [
					'id' => 8,
					'start' => '2025-10-14', 'end' => '2025-10-14T12:35:00',
					'group' => 'surrigagno',
					'content' => '2 OTTO'
				]
			]
		];
	}
}
