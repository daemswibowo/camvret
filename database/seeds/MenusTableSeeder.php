<?php

use Illuminate\Database\Seeder;
use App\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$menus = [
    		[
    			'fields' => [
    				'title' => 'Home',
    				'icon' => 'icon-home',
    				'to' => '{"name": "Read Home"}',
    				'type' => 'vue',
    			],
    			'childs' => [],
    			'permissions' => ['Read Home']
    		],
    		
    		[
    			'fields' => [
    				'title' => 'Management',
    				'icon' => 'fa fa-briefcase',
    				'to' => 'javascript:void(0)',
    				'type' => 'link',
    			],
    			'permissions' => [
                                    'Manage Permissions',
                                    'Manage Roles',
                                    'Manage Users',
    								'Manage Menus',
    							 ],
    			'childs' => [
    				[
    					'fields' => [
    						'title' => 'Permission',
    						'to' => '{"name":"Manage Permissions"}',
    						'type' => 'vue',
    					],
    					'permissions' => [
    						'Manage Permissions',
    					],
    					'childs' => [],
    				],
    				[
    					'fields' => [
    						'title' => 'Role',
    						'to' => '{"name":"Manage Roles"}',
    						'type' => 'vue',
    					],
    					'permissions' => [
    						'Manage Roles',
    					],
    					'childs' => [],
    				],
    				[
    					'fields' => [
    						'title' => 'User',
    						'to' => '{"name":"Manage Users"}',
    						'type' => 'vue',
    					],
    					'permissions' => [
    						'Manage Users',
    					],
    					'childs' => [],
    				],
                    [
                        'fields' => [
                            'title' => 'Menu',
                            'to' => '{"name":"Manage Menus"}',
                            'type' => 'vue',
                        ],
                        'permissions' => [
                            'Manage Menus',
                        ],
                        'childs' => [],
                    ],
    				[
    					'fields' => [
    						'title' => 'Oauth Clients',
    						'to' => '{"name":"Manage Oauth Clients"}',
    						'type' => 'vue',
    					],
    					'permissions' => [
    						'Manage Oauth Clients',
    					],
    					'childs' => [],
    				],
    			],
    		],

    	];

    	foreach ($menus as $key => $m) {
    		$this->create($m);
    	}
    }

    private function create ($menu, $parent_id=null) 
    {
    	$data = $menu['fields'];
    	$data = array_add($data, 'parent_id', $parent_id);
    	$m = Menu::create($data);
    	$m->syncPermissions($menu['permissions']);
    	if (count($menu['childs']) > 0) {
    		foreach ($menu['childs'] as $key => $c) {
    			$this->create($c, $m->id);
    		}
    	}
    }
}
