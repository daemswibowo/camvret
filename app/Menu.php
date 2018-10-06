<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Menu extends Model
{
	use HasRoles;
	
	protected $guard_name = 'web';
	protected $fillable = ['title','icon','to','type','position','parent_id'];

	public function recrusiveChilds () {
		return $this->hasMany('App\Menu','parent_id','id');
	}

	public function childs () {
		return $this->recrusiveChilds()->with(['childs','permissions'])->orderBy('position','asc');
	}

	public function render () {
		return Menu::whereParentId(null)->with(['childs','permissions'])->orderBy('position','asc')->get();
	}
}
