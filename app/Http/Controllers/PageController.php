<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{

    public function __construct() {

    }

    public function index(Request $request) {
        return view('pages.home');
    }

    public function home(Request $request, $page='dashboard') {
        $data['page'] = $page;
        $data['user'] = auth()->user();
        if (view()->exists("pages.{$page}")) {
            return view("pages.{$page}", $data);
        }
        return abort(404);
    }
}
