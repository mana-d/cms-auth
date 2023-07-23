<?php
function _menuByUserLevel()
{
	$userLogin 	= Auth::user();
	$userLevel 	= $userLogin->user_level_id;
	
	$menu 			= json_decode(json_encode(config('menu')));
	$userLevelMenu  = App\Models\UserLevelMenuV2::where('user_level_id', $userLevel)->pluck('menu_kode')->toArray();

	$listMenu = [];
	foreach ($menu as $mn) {
		$dataChildMenu = $mn->child_menu;
		if (sizeof($dataChildMenu) > 0) {
			$url = !empty($mn->route_name) && Route::has($mn->route_name) ?  route($mn->route_name) : '#';
			$detailMenu = [
				'icon' 	=> $mn->icon ? : '',
				'nama'	=> $mn->nama,
				'label' => $mn->label,
				'url' 	=> $url,
				'active' => '',
				'type' 	=> 1,
				'show'	=> '',
				'child-menu' => [],
			];
			$childMenu = array();
			$activeChildMenu = "";
			foreach ($dataChildMenu as $cmn) {
				if (in_array($cmn->kode, $userLevelMenu) || $userLevel == null) {
					$url = !empty($cmn->route_name) && Route::has($cmn->route_name) ?  route($cmn->route_name) : '#';
					$checkActiveChildMenu = _checkMenuActive($cmn->route_name);
					$childMenu[] = [
						'icon' 	=> "",
						'nama'	=> $cmn->nama,
						'label' => $cmn->label,
						'url' 	=> $url,
						'active' => $checkActiveChildMenu,
					];

					if ($activeChildMenu == "" && $checkActiveChildMenu == 'active') {
						$activeChildMenu = $checkActiveChildMenu;
					}
				}
			}

			$detailMenu['active'] = $activeChildMenu;
			$detailMenu['show'] = $activeChildMenu ? 'show' : '';

			if (sizeof($childMenu) > 0) {
				$detailMenu['child-menu'] = $childMenu;
				$listMenu[] = $detailMenu;
			}
		} else {
			if (in_array($mn->kode, $userLevelMenu) || $userLevel == null) {
				$url = !empty($mn->route_name) && Route::has($mn->route_name) ?  route($mn->route_name) : '#';
				$listMenu[] = [
					'icon' 	=> $mn->icon ? : '',
					'nama'	=> $mn->nama,
					'label' => $mn->label,
					'url' 	=> $url,
					'active' => _checkMenuActive($mn->route_name),
					'type' 	=> 0,
					'show'	=> '',
					'child-menu' => [],
				];
			}
		}
	}

	return $listMenu;
}

function _checkMenuActive($routeMenu)
{
    $routeName = Illuminate\Support\Facades\Route::current()->getName();
	// $groupName = explode('.',$routeName)[0];
	// $routeMenu = explode('.',$routeMenu)[0];
	
	$result = "";
	if (!empty($routeMenu) && $routeName == $routeMenu) {
		$result = "active";
	}
	
	return $result;
}

function _authorize($module, $task)
{
	$userLogin = Auth::user();
	$userLevel 	= $userLogin->user_level_id;

	$listUserLevelTask = App\Models\UserLevelModulTaskV2::where('user_level_id', $userLevel)->pluck('module_task_kode')->toArray();
	if ((sizeof($listUserLevelTask) && in_array($module.".".$task, $listUserLevelTask)) || $userLevel == null) {
		return true;
	}

	return false;
}

function _menuByMitraH2h()
{
	$userLogin 	= Auth::user();
	$userLevel 	= $userLogin->user_level_id;
	
	$menu 			= json_decode(json_encode(config('menuMitraH2h')));

	$listMenu = [];
	foreach ($menu as $mn) {
		$dataChildMenu = $mn->child_menu;
		if (sizeof($dataChildMenu) > 0) {
			$url = !empty($mn->route_name) && Route::has($mn->route_name) ?  route($mn->route_name) : '#';
			$detailMenu = [
				'icon' 	=> $mn->icon ? : '',
				'nama'	=> $mn->nama,
				'label' => $mn->label,
				'url' 	=> $url,
				'active' => '',
				'type' 	=> 1,
				'show'	=> '',
				'child-menu' => [],
			];
			$childMenu = array();
			$activeChildMenu = "";
			foreach ($dataChildMenu as $cmn) {
				$url = !empty($cmn->route_name) && Route::has($cmn->route_name) ?  route($cmn->route_name) : '#';
				$checkActiveChildMenu = _checkMenuActive($cmn->route_name);
				$childMenu[] = [
					'icon' 	=> "",
					'nama'	=> $cmn->nama,
					'label' => $cmn->label,
					'url' 	=> $url,
					'active' => $checkActiveChildMenu,
				];

				if ($activeChildMenu == "" && $checkActiveChildMenu == 'active') {
					$activeChildMenu = $checkActiveChildMenu;
				}
			}

			$detailMenu['active'] = $activeChildMenu;
			$detailMenu['show'] = $activeChildMenu ? 'show' : '';

			if (sizeof($childMenu) > 0) {
				$detailMenu['child-menu'] = $childMenu;
				$listMenu[] = $detailMenu;
			}
		} else {
			$url = !empty($mn->route_name) && Route::has($mn->route_name) ?  route($mn->route_name) : '#';
			$listMenu[] = [
				'icon' 	=> $mn->icon ? : '',
				'nama'	=> $mn->nama,
				'label' => $mn->label,
				'url' 	=> $url,
				'active' => _checkMenuActive($mn->route_name),
				'type' 	=> 0,
				'show'	=> '',
				'child-menu' => [],
			];
		}
	}

	return $listMenu;
}