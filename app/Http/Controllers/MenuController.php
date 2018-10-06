<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\MenuStoreRequest;
use App\Http\Requests\MenuUpdateRequest;
use App\Http\Controllers\Controller;

use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MenuController extends Controller
{

	public function __construct () 
    {
        $this->middleware('permission:Create Menus', ['only' => 'store']);
        // $this->middleware('permission:Read Menus', ['only' => 'index','show']);
        $this->middleware('permission:Update Menus', ['only' => 'update']);
        $this->middleware('permission:Delete Menus', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Menu $menu)
    {
        return $menu->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(MenuStoreRequest $request)
    {

        $menu = Menu::create([
            'title' => $request->title,
            'icon' => $request->icon,
            'to' => $request->to,
            'type' => $request->type,
            'position' => 0
        ]);
        $menu->syncPermissions($request->permissions);
        return response()->json($menu, 201);
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
        $menu = Menu::findOrFail($id);

        return $menu;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(MenuUpdateRequest $request, $id)
    {

        $menu = Menu::findOrFail($id);
        $menu->update($request->all());
        $menu->syncPermissions($request->permissions);
        return response()->json($menu, 200);
    }

    public function updateSort(Request $request) {
        foreach ($request->all() as $key => $item) {
            if ($item['item_id']) {
                $menu = Menu::find($item['item_id']);
                if ($menu) {
                    $menu->update(['parent_id'=>$item['parent_id'],'position'=>$item['left']]);
                }
            }
        }
        return response()->json('success', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $this->delete($id);
        return response()->json(null, 204);
    }

    /**
     * Delete menu with childs (recrusive)
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function delete ($id) 
    {
        $menu = Menu::findOrFail($id);
        $menu->syncPermissions([]);
        $menu->delete();
        $childs = Menu::whereParentId($id)->get();
        foreach ($childs as $key => $c) {
            $this->delete($c->id);
        }
    }
}
