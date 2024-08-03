<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimeSeriesController extends Controller
{
    // public function showUploadForm()
    // {
    //     return view('upload');
    // }

    // public function uploadCSV(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:csv,txt',
    //         'type' => 'required',
    //         'description' => 'nullable|string'
    //     ]);

    //     $path = $request->file('file')->store('csv');

    //     if ($request->input('type') === 'multivariate') {
    //         $data = array_map('str_getcsv', file(storage_path('app/' . $path)));
    //         $headers = $data[0];
    //         array_shift($data); // Remove header row

    //         return view('multivariate', compact('data', 'headers'));
    //     } else {
    //         $data = array_map('str_getcsv', file(storage_path('app/' . $path)));
    //         $headers = $data[0];
    //         array_shift($data); // Remove header row

    //         return view('univariate', compact('data', 'headers'));
    //     }
    // }

    // public function uploadProcessedCSV(Request $request)
    // {
    //     $file = $request->file('file');
    //     $path = $file->store('processed_csvs');

    //     // Process the file or save the path to the database

    //     return response()->json(['message' => 'Processed CSV uploaded successfully', 'path' => $path]);
    // }
}

