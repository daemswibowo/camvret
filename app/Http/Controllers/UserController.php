<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    public function __construct () 
    {
        $this->middleware('permission:Create Users', ['only' => 'store']);
        $this->middleware('permission:Read Users', ['only' => 'index','show']);
        $this->middleware('permission:Update Users', ['only' => 'update']);
        $this->middleware('permission:Delete Users', ['only' => 'destroy']);
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
        $user = User::with(['permissions','roles.permissions'])->where(function($q) use($request) {
            if (!$request->user()->hasRole('Superadmin')) {
                $q->whereDoesntHave('roles', function($q) {
                    $q->whereIn('name', ['Superadmin']);
                });
            }
        });

        if (!empty($keyword)) {
            $users = $user->where(function ($q) use ($keyword) {
                $columns = Schema::getColumnListing('users');
                foreach ($columns as $key => $column) {
                    if ($key==0) {
                        $q->where($column, 'like', "%$keyword%");
                    } else {
                        $q->orWhere($column, 'like', "%$keyword%");
                    }
                }
            })->paginate($perPage);
        } else if ($request->get('all')) {
            $users = $user->get();
        } else {
            $users = $user->paginate($perPage);
        }
        
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        if (count($request->roles) > 0) {
            $user->syncRoles($request->roles);
        }
        if ($request->user()->can('Give Permissions To User') && count($request->permissions) > 0) {
            $user->syncPermissions($request->permissions);
        }

        return response()->json($user, 201);
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
        $user = User::findOrFail($id);

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.',id',
        ];

        if (!empty($request->password)) {
            $validation = array_add($validation, 'password', 'min:6|confirmed');
        }

        $request->validate($validation);
        $user = User::findOrFail($id);
        $requestData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if (!empty($request->password)) {
            $requestData = array_add($requestData, 'password', bcrypt($request->password));
        }
        $user->update($requestData);
        $user->syncRoles($request->roles);
        if ($request->user()->can('Give Permissions To User')) {
            $user->syncPermissions($request->permissions);
        }
        return response()->json($user, 200);
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
        User::destroy($id);

        return response()->json(null, 204);
    }

    public function updateProfilePicture (Request $request) 
    {
        $request->validate([
            'image' => "required|image|max:300",
        ]);

        $file = $request->file('image');
        $path = $file->hashName('public/profiles');
        $img = \Image::make($file);
        $img->fit(150);
        \Storage::put($path, (string) $img->encode());
        $url = \Storage::url($path);

        if ($request->user()->image) {
            # jika user sudah ada image
            # Hapus file lama sebelum update
            $image = json_decode($request->user()->image);
            \Storage::delete($image->path);
        }

        $request->user()->image = json_encode(['path' => $path, 'url' => $url]);
        $request->user()->save();

        return $request->user();
    }

    public function updateProfile (Request $request)
    {
        $validation = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->user()->id.',id',
        ];

        if (!empty($request->password)) {
            $validation = array_add($validation, 'password', 'min:6|confirmed');
        }

        $request->validate($validation);


        $requestData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if (!empty($request->password)) {
            $requestData = array_add($requestData, 'password', bcrypt($request->password));
        }

        $request->user()->update($requestData);
        return $request->user();
    }
}
