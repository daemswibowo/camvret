<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;

class RoleController extends Controller
{
    public function __construct () 
    {
        $this->middleware('permission:Create Roles', ['only' => 'store']);
        $this->middleware('permission:Read Roles', ['only' => 'index','show']);
        $this->middleware('permission:Update Roles', ['only' => 'update']);
        $this->middleware('permission:Delete Roles', ['only' => 'destroy']);
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
        $role = Role::with('permissions')->where(function ($q) use ($request) {
            if (!$request->user()->hasRole('Superadmin')) {
                $q->whereNotIn('name', ['Superadmin']);
            }
        });
        if (!empty($keyword)) {
            $roles = $role->where(function ($q) use ($keyword) {
                $columns = Schema::getColumnListing('roles');
                foreach ($columns as $key => $column) {
                    if ($key==0) {
                        $q->where($column, 'like', "%$keyword%");
                    } else {
                        $q->orWhere($column, 'like', "%$keyword%");
                    }
                }
            })->paginate($perPage);
        } else if ($request->get('all')) {
            $roles = $role->latest()->get();
        } else {
            $roles = $role->latest()->paginate($perPage);
        }

        return $roles;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);
        $role->syncPermissions($request->permissions);
        return response()->json($role, 201);
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
        $role = Role::findOrFail($id);

        return $role;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, $id)
    {

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);
        $role->syncPermissions($request->permissions);
        
        return response()->json($role, 200);
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
        Role::destroy($id);

        return response()->json(null, 204);
    }
}
