<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\UserLevel;
use App\Models\UserLevelMenu;
use App\Models\UserLevelMenuModeluTask;
use App\Models\UserLevelModuleTask;
use DB;
use Illuminate\Http\Request;

class UserLevelController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'serach' => 'nullable'
        ]);

        $search = $request->search;

        $listData  = UserLevel::select('user_levels.id', 'user_levels.name', 'parent.name as parent_name', 'user_levels.created_at')
            ->leftJoin('user_levels as parent', 'parent.id', 'user_levels.parent_user_level_id')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('user_levels.name', 'like', '%' . $search . '%');
            })
            ->orderBy('user_levels.created_at', 'DESC')
            ->paginate(20);

        $listData->appends($request->input());

        $totalData = $listData->total();
        $firstData = ($listData->currentPage() - 1) * $listData->perPage();
        $lastData  = ($firstData + $listData->perPage()) > $totalData ? $totalData : ($firstData + $listData->perPage());

        $paginationData = [
            'first' => $firstData,
            'last'  => $lastData,
            'total' => $totalData,
            'prev_page_url' => $listData->previousPageUrl(),
            'next_page_url' => $listData->nextPageUrl(),
        ];

        $listUserLevel = UserLevel::select('id', 'name', 'level')->get()->groupBy('level');

        return view('cms.user-level', compact('listData', 'paginationData', 'listUserLevel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'parent_user_level_id' => 'nullable'
        ]);

        $userId = null; //Auth::user()->id;
        $parentUserLevelId = $request->parent_user_level_id ?: null;

        $level  = 1;
        if ($parentUserLevelId) {
            $dataParentUserLevel = UserLevel::where('id', $parentUserLevelId)->first();
            $level += $dataParentUserLevel->level;
        }

        try {
            UserLevel::insert([
                'name'                 => $request->name,
                'parent_user_level_id' => $request->parent_user_level_id,
                'level'                => $level,
                'created_user_id'      => $userId,
                'created_at'          => date("Y-m-d H:i:s")
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Database Error",
                'debug'   => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => "OK"
        ]);
    }

    public function detail($id)
    {
        $userLevelData = UserLevel::select('id', 'name', DB::raw("IF(parent_user_level_id is null, '', parent_user_level_id) as parent_user_level_id"))
            ->where('id', $id)
            ->first();

        if (empty($userLevelData)) {
            return response()->json([
                'status'  => "ERROR",
                'message' => "Data tidak ditemukan"
            ]);
        }

        return response()->json([
            'status'  => "OK",
            'results' => $userLevelData
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'                 => 'required|string',
            'parent_user_level_id' => 'nullable'
        ]);

        $userId = null; //Auth::user()->id;
        $parentUserLevelId = $request->parent_user_level_id ?: null;

        $level  = 1;
        if ($parentUserLevelId) {
            $dataParentUserLevel = UserLevel::where('id', $parentUserLevelId)->first();
            $level += $dataParentUserLevel->level;
        }

        try {
            UserLevel::where('id', $id)
                ->update([
                    'name'                 => $request->name,
                    'parent_user_level_id' => $parentUserLevelId,
                    'level'                => $level,
                    'updated_at'           => date("Y-m-d H:i:s")
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Database Error",
                'debug'   => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => "OK"
        ]);
    }

    public function delete($id)
    {
        try {
            UserLevel::where('id', $id)->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Database Error",
                'debug'   => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => "OK"
        ]);
    }

    public function setting($id)
    {
        $dataUserLevel       = UserLevel::where('id', $id)->first();
        $userLevelMenu       = UserLevelMenu::where('user_level_id', $id)->pluck('menu_code')->toArray();
        $userLevelModuleTask = UserLevelModuleTask::where('user_level_id', $id)->pluck('module_task_code')->toArray();

        $name = $dataUserLevel->name;

        $menu           = json_decode(json_encode(config('menu')));
        $dataMenu       = [];
        foreach ($menu as $mn) {
            $menuChild  = [];
            $listCode   = [];
            $countCheck = 0;
            foreach ($mn->child as $cm) {
                $listCode[]  = $cm->code;
                $countCheck += in_array($cm->code, $userLevelMenu) ? 1 : 0;

                $childModuleTask = [];
                foreach ($cm->module->task as $task) {
                    $moduleTaskcode = $cm->module->code . "." . $task->code;

                    $childModuleTask[] = (object) [
                        'code'  => $moduleTaskcode,
                        'name'  => $task->name,
                        'check' => in_array($moduleTaskcode, $userLevelModuleTask) ? 1 : 0
                    ];
                }

                $menuChild[] = (object) [
                    'code'        => $cm->code,
                    'name'        => $cm->name,
                    'check'       => in_array($cm->code, $userLevelMenu) ? 1 : 0,
                    'module_code' => $cm->module->code,
                    'module_task' => $childModuleTask
                ];
            }

            $code = $mn->code;

            $check = in_array($mn->code, $userLevelMenu) || ($countCheck > 0 && $countCheck == count($listCode))  ? 1 : 0;

            $moduleCode = "";
            $moduleTask = [];

            if (!empty($mn->module)) {
                $moduleCode = $mn->module->code;

                foreach ($mn->module->task as $task) {
                    $moduleTaskCode = $mn->module->code . "." . $task->code;

                    $moduleTask[] = (object) [
                        'code'  => $moduleTaskCode,
                        'name'  => $task->name,
                        'check' => in_array($moduleTaskCode, $userLevelModuleTask) ? 1 : 0
                    ];
                }
            }

            $dataMenu[] = (object) [
                'code'        => $code,
                'name'        => $mn->name,
                'check'       => $check,
                'module_code' => $moduleCode,
                'module_task' => $moduleTask,
                'child'       => $menuChild
            ];
        }

        $moduleTask = json_decode(json_encode(config('moduletask')));
        $dataModule = [];
        foreach ($moduleTask as $data) {
            $task       = [];
            $listCode   = [];
            $countCheck = 0;
            foreach ($data->task as $dataTask) {
                $listCode[] = $dataTask->code;
                $countCheck += in_array($dataTask->code, $userLevelModuleTask) ? 1 : 0;
                $task[]     = (object) [
                    'code'  => $dataTask->code,
                    'name'  => $dataTask->name,
                    'check' => in_array($dataTask->code, $userLevelModuleTask) ? 1 : 0
                ];
            }

            $dataModule[] = (object) [
                'name'  => $data->name,
                'check' => ($countCheck > 0 && $countCheck == count($listCode))  ? 1 : 0,
                'task'  => $task
            ];
        }

        return view('cms.user-level-setting', compact('id', 'name', 'dataMenu', 'dataModule'));
    }

    public function updateSetting(Request $request, $id)
    {
        $request->validate([
            "tipe"         => 'required',
            "codes"        => 'array|required',
            "codes.*"      => 'required',
            "module_codes" => 'array|nullable',
            "checked"      => ''
        ]);

        $userLevelId    = $id;
        $tipe           = $request->tipe;
        $codes          = $request->codes;
        $moduleCodes    = $request->module_codes;
        $checked        = $request->checked;

        if ($tipe == 'menu') {
            $newModuleCodes = [];
            foreach ($moduleCodes as $data) {
                $newModuleCodes[] = $data . ".hak-akses";
            }

            $moduleCodes = $newModuleCodes;

            if ($checked) {
                foreach ($codes as $data) {
                    try {
                        UserLevelMenu::insert([
                            'user_level_id' => $userLevelId,
                            'menu_code'     => $data,
                        ]);
                    } catch (\Exception $e) { }
                }

                foreach ($moduleCodes as $data) {
                    try {
                        UserLevelModuleTask::insert([
                            'user_level_id'    => $userLevelId,
                            'module_task_code' => $data,
                        ]);
                    } catch (\Exception $e) { }
                }
            } else {
                UserLevelMenu::where('user_level_id', $userLevelId)
                    ->whereIn('menu_code', $codes)
                    ->delete();

                UserLevelModuleTask::where('user_level_id', $userLevelId)
                    ->whereIn('module_task_code', $moduleCodes)
                    ->delete();
            }
        } else if ($tipe == 'module-task') {
            if ($checked) {
                foreach ($codes as $data) {
                    try {
                        UserLevelModuleTask::insert([
                            'user_level_id'    => $userLevelId,
                            'module_task_code' => $data,
                        ]);
                    } catch (\Exception $e) { }
                }
            } else {
                UserLevelModuleTask::where('user_level_id', $userLevelId)
                    ->whereIn('module_task_code', $codes)
                    ->delete();
            }
        }

        return response()->json([
            'status' => 'OK'
        ]);
    }
}
