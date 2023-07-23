<?php
namespace App\Libraries;

use App\Models\UserLevelMenu;
use Auth;
use Illuminate\Support\Facades\Route;

class ManaCms
{
	public static function listMenu()
    {
        // $userLogin 	= Auth::user();
        $userLevel 	= null; //$userLogin->user_level_id;
        
        $menus = json_decode(json_encode(config('menu')));

        $userLevelMenuCodes  = UserLevelMenu::where('user_level_id', $userLevel)->pluck('menu_code')->toArray();

        $listMenu = [];
        foreach ($menus as $menu) {
            $dataMenuChild = $menu->child;

            $listMenuChild   = array();
            $activeMenuChild = false;
            foreach ($dataMenuChild as $child) {
                if ($userLevel == null || in_array($child->code, $userLevelMenuCodes)) {
                    $url = !empty($child->route_name) && Route::has($child->route_name) ?  route($child->route_name) : '#';
                    $checkActiveMenuChild = ManaCms::checkMenuActive($child->route_name);
                    $listMenuChild[] = (object) [
                        'name'	 => $child->name,
                        'label'  => $child->label,
                        'icon' 	 => $child->icon,
                        'url' 	 => $url,
                        'active' => $checkActiveMenuChild ? "active" : "",
                    ];

                    if (!$activeMenuChild && $checkActiveMenuChild) {
                        $activeMenuChild = $checkActiveMenuChild;
                    }
                }
            }
            
            if ($userLevel == null || in_array($menu->code, $userLevelMenuCodes) || sizeof($listMenuChild) > 0) {
                $icon = $menu->icon && view()->exists('components.icons.'.$menu->icon) ? $menu->icon : "";

                $url = !empty($menu->route_name) && Route::has($menu->route_name) ?  route($menu->route_name) : '#';

                $active = $activeMenuChild || ManaCms::checkMenuActive($menu->route_name) ? "active" : "";
                
                $listMenu[] = (object) [
                    'name'	 => $menu->name,
                    'label'  => $menu->label,
                    'icon' 	 => $icon,
                    'url' 	 => $url,
                    'active' => $active,
                    'show'	 => $activeMenuChild ? "show" : "",
                    'childs' => $listMenuChild,
                ];
            }
        }

        return $listMenu;
    }

    public static function checkMenuActive($routeMenu)
    {
        $routeName = Route::current()->getName();
        $groupName = explode('.',$routeName)[0];
        $routeMenu = explode('.',$routeMenu)[0];

        $result = false;
        if (!empty($routeMenu) && $groupName == $routeMenu) {
            $result = true;
        }
        
        return $result;
    }
}