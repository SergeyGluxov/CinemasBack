<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //----------------------------------ПОЛЬЗОВАТЕЛИ--------------------------------------------------------------------
    public function getContents()
    {
        return view('admin/content/all');
    }

    public function getContent($id)
    {
        return view('admin/content/content', compact('id', 'id'));
    }

    public function getFeeds()
    {
        return view('admin/feed/all');
    }

}
