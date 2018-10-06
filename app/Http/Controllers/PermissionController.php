<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PermissionController extends Controller
{

    public function __construct () 
    {
        $this->middleware('permission:Create Permissions', ['only' => 'store']);
        $this->middleware('permission:Read Permissions', ['only' => 'index','show']);
        $this->middleware('permission:Update Permissions', ['only' => 'update']);
        $this->middleware('permission:Delete Permissions', ['only' => 'destroy']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 10;

        if (!empty($keyword)) {
            $permissions = Permission::where(function ($q) use ($keyword) {
                $columns = Schema::getColumnListing('permissions');
                foreach ($columns as $key => $column) {
                    if ($key==0) {
                        $q->where($column, 'like', "%$keyword%");
                    } else {
                        $q->orWhere($column, 'like', "%$keyword%");
                    }
                }
            })->paginate($perPage);
        } else if ($request->get('all')) {
            $permissions = Permission::latest()->get();
        } else {
            $permissions = Permission::latest()->paginate($perPage);
        }

        return $permissions;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionStoreRequest $request)
    {

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return response()->json($permission, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);

        return $permission;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionUpdateRequest $request, $id)
    {

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return response()->json($permission, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Permission::destroy($id);

        return response()->json(null, 204);
    }
}
