<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstallController extends Controller
{
    public function index  ()
    {
		return view('install');
    }

    public function store (Request $request) 
    {
		$key = env('APP_KEY');
		$request->validate([
			'app_key' => 'required',
		]);
		if ($request->app_key!=$key) {
			return redirect()->back()->withErrors(['Wrong key!']);
		}

		\Artisan::call("migrate:refresh", ['--force' => true]);
		\Artisan::call("db:seed");

		return "Install success!";
    }
}
