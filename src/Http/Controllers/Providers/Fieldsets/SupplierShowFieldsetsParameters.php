<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Clients\Models\Client;
use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use IlBronza\Operators\Models\Operator;

use function array_keys;
use function get_class;
use function implode;

class SupplierShowFieldsetsParameters extends FieldsetParametersFile
{
	public function getDocumentCategoryName() : string
	{
		if($this->getModel()->getTarget() instanceof Operator)
			return 'documenti-anagrafici';

		if($this->getModel()->getTarget() instanceof Client)
			return 'documenti-aziendali';

		throw new \Exception('Document category not found for this modeltype ' . get_class($this->getModel()->getTarget()));
	}

	public function _getFieldsetsParameters() : array
	{
		$supplier = $this->getModel();

		$paymentTypes = $supplier->getPossiblePaymenttypesValuesArray();

		$result = [
			'card' => [
				'classes' => ['logo-card'],
				'showLegend' => false,
				'fields' => [],
				'fieldsets' => [
					'badge_image' => [
						'showLegend' => false,
						'fields' => [],
						'view' => [
							'name' => 'crud::utilities.logo.logo',
							'parameters' => [
								'modelInstance' => $this->getModel()->getTarget()
							]
						],
					],
					'contacts' => [
						'showLegend' => false,
						'fields' => [],
						'view' => [
							'name' => 'contacts::contacts._fetcherModelContacts',
							'parameters' => [
								'model' => $this->getModel()->getTarget()
							]
						],
						'width' => ['medium']
					],
				],
				'width' => ['medium']
			],

			'payments' => [
				'translationPrefix' => 'clients::fields',
				'fields' => [
					'paymenttype_id' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|nullable|in:' . implode(',', array_keys($paymentTypes)),
						'list' => $paymentTypes
					],
					'target' => [
						'type' => 'select',
						'readOnly' => true,
						'select2' => false,
						'multiple' => false,
						'rules' => 'string|nullable|in:' . implode(',', array_keys($paymentTypes)),
						'relation' => 'target'
					]
				],
				'width' => ['medium']
			],

			'notes' => [
				'fields' => [],
				'view' => [
					'name' => 'notes::notes',
					'parameters' => [
						'modelInstance' => $this->getModel()->getTarget(),
					],
				],
				'width' => ['xlarge']
			],
			'documents' => [
				'fields' => [],
				'view' => [
					'name' => 'filecabinet::fetchers._modelDossiersByCategory',
					'parameters' => [
						'categorySlug' => $this->getDocumentCategoryName(),
						'model' => $this->getModel()->getTarget()
					]
				],
				'width' => ['large']
			],
		];

		return $result;
	}
}
