<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\FileAssociation;
use Auth;
use Storage;

class FileController extends Controller
{


    public function upload_file_to_project(Request $request)
    {
        /*
        This function stores the uploaded file from the project. 
        It also extract other information such as type, freq, and description
        */

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

        return redirect()->back()->with('success', 'File uploaded successfully');
    }







    public function upload_file_to_user(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'type' => 'required',
            'freq' => 'required',
            'description' => 'nullable|string',
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

        if ($request->input('type') === 'multivariate') {
            $data = array_map('str_getcsv', file(storage_path('app/' . $path)));
            $headers = $data[0];
            array_shift($data);

            return view('analyze.multivariate', compact('data', 'headers'));
        } else {
            $data = array_map('str_getcsv', file(storage_path('app/' . $path)));
            $headers = $data[0];
            array_shift($data);

            return view('analyze.univariate', compact('data', 'headers'));
        }


        // // Generate initial JSON result for the uploaded file
        // $jsonContent = ['message' => 'Initial JSON result for ' . $filename];
        // $jsonFilename = pathinfo($filename, PATHINFO_FILENAME) . '-initial-' . now()->timestamp . '.json';
        // $jsonPath = 'uploads/' . $jsonFilename;
        // Storage::put($jsonPath, json_encode($jsonContent));

        // /* We will perform this part once we have the json file of the actual result */
        // FileAssociation::create([
        //     'file_id' => $uploadedFile->id,
        //     'associated_file_path' => $jsonPath,
        //     'associated_by' => Auth::id(),
        // ]);

        // return redirect()->back()->with('success', 'File uploaded and JSON result generated successfully');
    }



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

    public function associateUserJson_from_analyze(Request $request)
    {
        //from this we extract the file
        $id = $request->get("file_id");

        $file = File::where('user_id', Auth::id())->where('id', $id)->whereNull('project_id')->first();

        // Generate associated JSON file
        $jsonContent = ['message' => 'This is a system-generated JSON file for ' . $file->filename];
        $jsonFilename = pathinfo($file->filename, PATHINFO_FILENAME) . '-assoc-' . now()->timestamp . '.json';
        $jsonPath = 'uploads/' . $jsonFilename;
        Storage::put($jsonPath, json_encode($jsonContent));

        echo Auth::id();
        // Create a new FileAssociation record
        FileAssociation::create([
            'file_id' => $file->id,
            'associated_file_path' => $jsonPath,
            'associated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'File associated with JSON successfully');


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
