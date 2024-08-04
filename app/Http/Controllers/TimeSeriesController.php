<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimeSeriesController extends Controller
{
    public function receive_data(Request $request)
    {
        // Access the CSV file
        if ($request->hasFile('csv_file')) {
            $csvFile = $request->file('csv_file');
            $csvContent = file_get_contents($csvFile->getRealPath());

            // Process CSV content here
            // For example, you might use a library like Laravel Excel to parse the CSV
        }

        // Access other form variables
        $steps = $request->input('steps');
        $method = $request->input('method');
        $window_size = $request->input('window_size');
        $seasonal = $request->input('seasonal');

        // Process the form data
        // ...

        return response()->json(['success' => true, 'message' => 'Data received successfully.']);
    }
}
