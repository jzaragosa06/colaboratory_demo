<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\File;
// use App\Models\Project;
// use Auth;
// use Storage;

// class FileController extends Controller
// {
//     public function store(Request $request)
//     {
//         $request->validate([
//             'file' => 'required|file',
//             'project_id' => 'nullable|exists:projects,id'
//         ]);

//         $file = $request->file('file');
//         $filename = $file->getClientOriginalName();
//         $path = $file->storeAs('uploads', $filename);

//         File::create([
//             'user_id' => Auth::id(),
//             'project_id' => $request->project_id,
//             'filename' => $filename,
//             'path' => $path,
//         ]);

//         return redirect()->back()->with('success', 'File uploaded successfully');
//     }
// }


// app/Http/Controllers/FileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Project;
use Auth;
use Storage;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('uploads', $filename);

        File::create([
            'user_id' => Auth::id(),
            'project_id' => $request->project_id,
            'filename' => $filename,
            'path' => $path,
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully');
    }
}
