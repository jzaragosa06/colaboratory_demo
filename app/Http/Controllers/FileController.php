<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\FileAssociation;
use Auth;
use Storage;

class FileController extends Controller
{
    public function store_from_project(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('uploads', $filename);

        $uploadedFile = File::create([
            'user_id' => Auth::id(),
            'project_id' => $request->project_id,
            'filename' => $filename,
            'path' => $path,
        ]);

        return redirect()->back()->with('success', 'File uploaded and JSON result generated successfully');
    }

    public function store_not_from_project(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('uploads', $filename);

        $uploadedFile = File::create([
            'user_id' => Auth::id(),
            'project_id' => $request->project_id,
            'filename' => $filename,
            'path' => $path,
        ]);

        // Generate initial JSON result for the uploaded file
        $jsonContent = ['message' => 'Initial JSON result for ' . $filename];
        $jsonFilename = pathinfo($filename, PATHINFO_FILENAME) . '-initial-' . now()->timestamp . '.json';
        $jsonPath = 'uploads/' . $jsonFilename;
        Storage::put($jsonPath, json_encode($jsonContent));

        FileAssociation::create([
            'file_id' => $uploadedFile->id,
            'associated_file_path' => $jsonPath,
            'associated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'File uploaded and JSON result generated successfully');
    }

    public function showUserFiles()
    {
        // $files = File::where('user_id', Auth::id())->get();
        // return view('user.files', compact('files'));
        $files = File::where('user_id', Auth::id())->whereNull('project_id')->get();
        return view('analyze.analyze_data', compact('files'));

    }

    public function showResults()
    {
        $files = File::where('user_id', Auth::id())->whereNull('project_id')->get();
        return view('results', compact('files'));
    }


    public function uploadedFiles()
    {
        return view('uploadedFiles');
    }
    // public function associateUserJson(Request $request)
    // {
    //     $request->validate([
    //         'file_id' => 'required|exists:files,id'
    //     ]);

    //     $file = File::find($request->file_id);

    //     if ($file->user_id !== Auth::id()) {
    //         return redirect()->back()->with('error', 'You can only associate JSON with your own files');
    //     }

    //     // Generate new JSON result for the selected file
    //     $jsonContent = ['message' => 'New JSON result for ' . $file->filename];
    //     $jsonFilename = pathinfo($file->filename, PATHINFO_FILENAME) . '-new-' . now()->timestamp . '.json';
    //     $jsonPath = 'uploads/' . $jsonFilename;
    //     Storage::put($jsonPath, json_encode($jsonContent));

    //     FileAssociation::create([
    //         'file_id' => $file->id,
    //         'associated_file_path' => $jsonPath,
    //         'associated_by' => Auth::id(),
    //     ]);

    //     return redirect()->back()->with('success', 'File associated with new JSON result successfully');
    // }

    public function associateUserJson(Request $request, File $file)
    {

        if ($file->is_active) {
            // if ($file->user_id !== Auth::id()) {
            //     return redirect()->back()->with('error', 'You can only associate JSON with your own files');
            // }

            // Generate associated JSON file
            $jsonContent = ['message' => 'This is a system-generated JSON file for ' . $file->filename];
            $jsonFilename = pathinfo($file->filename, PATHINFO_FILENAME) . '-assoc-' . now()->timestamp . '.json';
            $jsonPath = 'uploads/' . $jsonFilename;
            Storage::put($jsonPath, json_encode($jsonContent));

            // Create a new FileAssociation record
            FileAssociation::create([
                'file_id' => $file->id,
                'associated_file_path' => $jsonPath,
                'associated_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'File associated with JSON successfully');


        } else {
            return redirect()->back()->with('error', 'Only the active file can be associated with a JSON result.');

        }
    }

    public function makeActive(File $file)
    {
        // Deactivate all other files in the project
        File::where('project_id', $file->project_id)->update(['is_active' => false]);

        // Activate the selected file
        $file->is_active = true;
        $file->save();

        return redirect()->back()->with('success', 'File has been made active.');
    }



}
