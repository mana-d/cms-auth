<?php

return [
	[
		'kode'		 => 'dashboard',
		'nama'		 => "Dashboard",
		'label'		 => "dashboard",
		'icon'		 => "fas fa-tachometer-alt fa-fw",
		'route_name' => "dashboard",
		'module'	 => [
			'kode' => 'dashboard',
			'task' => []
		],
		'child_menu' => [],
	],
	[
		'kode'		 => 'system',
		'icon'		 => "fas fa-cogs fa-fw",
		'nama'		 => "System",
		'label'		 => "system",
		'route_name' => "",
		'module' 	 => [],
		'child_menu' => [
			[
				'kode'		 => "user-level",
				'nama'		 => "User Level",
				'label'		 => "userLevel",
				'route_name' => "systemUserLevel",
				'module' 	 => [
					'kode' => 'system-user-level',
					'task' => []
				]
			],
			[
				'kode'		 => "user",
				'nama'		 => "User",
				'label'		 => "user",
				'route_name' => "systemUser",
				'module' 	 => [
					'kode' => 'system-user',
					'task' => []
				]
			],
		],
	]
];