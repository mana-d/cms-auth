<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'serach' => 'nullable'
        ]);

        $search = $request->search;

        $listData  = User::join('user_levels', 'user_levels.id' ,'users.user_level_id')
            ->select('users.id', 'users.name', 'user_levels.name as user_level_name', 'users.username', 'users.email', 'users.flag_active', 'users.created_at')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('users.name', 'like', '%' . $search . '%');
            })
            ->orderBy('users.id', 'desc')
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

        return view('cms.user', compact('listData', 'paginationData', 'listUserLevel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string',
            'user_level' => 'required'
        ]);

        $userId = null; //Auth::user()->id;

        try {
            User::insert([
                'name'            => $request->name,
                'email'           => $request->email,
                'password'        => bcrypt($request->password),
                'user_level_id'   => $request->user_level,
                'created_at'      => date("Y-m-d H:i:s")
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
        $data = User::select('id', 'name', 'email', 'user_level_id')
            ->where('id', $id)
            ->first();

        if (empty($data)) {
            return response()->json([
                'status'  => "ERROR",
                'message' => "Data tidak ditemukan"
            ]);
        }

        return response()->json([
            'status'  => "OK",
            'results' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'       => 'required|string',
            'email'      => 'required|email|unique:users,email,' . $id,
            'password'   => 'nullable|string',
            'user_level' => 'required'
        ]);

        $userId = null; //Auth::user()->id;

        $dataUpdate = [
            'name'            => $request->name,
            'email'           => $request->email,
            'user_level_id'   => $request->user_level,
            'updated_at'      => date("Y-m-d H:i:s")
        ];

        if ($request->password) $dataUpdate['password'] = bcrypt($request->password);

        try {
            User::where('id', $id)->update($dataUpdate);
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

    public function updateActive(Request $request, $id)
    {
        $request->validate([
            'flag_active' => 'required',
        ]);

        $userId = null; //Auth::user()->id;

        try {
            User::where('id', $id)->update([
                'flag_active' => $request->flag_active ? 1 : 0,
                'updated_at'  => date("Y-m-d H:i:s")
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
            User::where('id', $id)->delete();
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
}
