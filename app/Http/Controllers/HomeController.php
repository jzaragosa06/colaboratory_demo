<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileAssociation;
use Auth;
use Storage;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function showInvitation()
    {
        return view('invitations.index');
    }

    public function showResults()
    {
        $files = File::where('user_id', Auth::id())->whereNull('project_id')->get();
        return view('profile.results', compact('files'));
    }


    public function showProfile()
    {
        return view('profile.my_profile');
    }



    public function uploadedFiles()
    {
        return view('profile.uploadedFiles');
    }

}
