<?php
/**
 * Configuración default para el componente text
 */

return [
	'name' => 'Visor de unidades',
	'description' => 'Un diagrama de árbol para poder visualizar correctamente las unidades del sistema',
	'general' => [
		'nombre' => [
			'type' => 'varchar',
			'name' => 'Nombre',
			'detail' => 'Nombre del componente',
			'required' => false,
			'value' => 'Texto',
		],
		'ancho' => [
			'type' => 'enum',
			'name' => 'Anchura del componente',
			'detail' => 'Ancho de la caja del componente',
			'required' => false,
			'default_values' => [
				'3' => '25%',
				'4' => '30%',
				'6' => '50%',
				'8' => '60%',
				'9' => '75%',
				'12' => '100%',
			],
			'value' => '4',
		],
		'alto' => [
			'type' => 'enum',
			'name' => 'Altura del componente',
			'detail' => 'Ancho de la caja del componente',
			'required' => false,
			'default_values' => [
				'h-md-auto' => 'Auto',
				'h-md-25' => '25%',
				'h-md-50' => '50%',
				'h-md-75' => '75%',
				'h-md-100' => '100%',
			],
			'value' => 'h-md-50',
		],
	],
];