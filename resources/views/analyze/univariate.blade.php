{{-- 

<!DOCTYPE html>
<html>

<head>
    <title>Univariate Data Processing</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</head>

<body>
    <div class="container">
        <h1>Univariate Data Processing</h1>
        <div class="row">
            <div class="col-md-12">
                <div id="chart-container">
                    <canvas id="chart"></canvas>
                </div>
                <div id="processing-options">
                    <h3>Data Cleaning and Processing</h3>
                    <form id="processing-form">
                        <label>Fill Missing Value (NaN) with:</label><br>
                        <input type="radio" name="fill-method" value="forward"> Forward Fill<br>
                        <input type="radio" name="fill-method" value="backward"> Backward Fill<br>
                        <input type="radio" name="fill-method" value="average"> Average of the series<br>
                        <input type="radio" name="fill-method" value="zero"> Fill with zeros<br>
                    </form>
                </div>
            </div>
        </div>
        <button type="button" id="next-button" class="btn btn-primary" data-toggle="modal"
            data-target="#resultMethod">Next</button>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="resultMethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Options for the Result</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modalForm">
                        <div class="form-group">
                            <label for="steps">Forecasting Method</label><br>
                            maximum steps (m) to forecast:<br>

                            <input type="number" id ="steps" name="steps" required>
                            <p>How the forecast will be made? </p>
                            <input type="radio" name="method" value = "with_refit">With
                            Refit<br>
                            <input type="radio" name="method" value = "without_refit">Without
                            Refit <br>

                        </div>
                        <div class="form-group">
                            <label for="window_size">Trend Analysis Method</label>
                            <p>Specify the window size: </p><br>
                            <select name="window_size" id = "window_size" class="form-control" required>
                                <option value="5">5-units window moving average</option>
                                <option value="10">10-units window moving average</option>
                                <option value="20">20-units window moving average</option>
                                <option value="30">30-units window moving average</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="seasonal">Seasonality analysis</label>
                            <p>Does the data exhibit seasonal behaviour?</p>
                            <input type="radio" name="seasonal" value = "yes">Yes <br>
                            <input type="radio" name="seasonal" value = "no">No<br>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id = "submit-button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const data = @json($data);
        const headers = @json($headers);
        let originalData = JSON.parse(JSON.stringify(data)); // Deep copy of original data
        let tempData = JSON.parse(JSON.stringify(originalData));
        let chartInstance = null; // Variable to store the current chart instance

        const chartCanvas = document.getElementById('chart');

        // Ensure that data is valid
        if (data && data.length > 0 && headers.length > 1) {
            const values = originalData.map(row => parseFloat(row[1]) || null); // Handle NaN as null
            const dates = originalData.map(row => new Date(row[0]));
            const label = headers[1];

            showChart(label, dates, values);
        } else {
            console.error('Invalid data or headers');
        }

        function showChart(label, dates, values) {
            // Destroy existing chart if it exists
            if (chartInstance) {
                chartInstance.destroy();
            }
            const ctx = chartCanvas.getContext('2d');
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: label,
                        data: values,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            },
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: label
                            }
                        }
                    }
                }
            });
        }

        function fillMissingValues(method) {
            tempData = JSON.parse(JSON.stringify(originalData)); // Reset tempData to original each time

            switch (method) {
                case 'forward':
                    for (let i = 1; i < tempData.length; i++) {
                        if (tempData[i][1] === null || tempData[i][1] === '') {
                            tempData[i][1] = tempData[i - 1][1];
                        }
                    }
                    break;
                case 'backward':
                    for (let i = tempData.length - 2; i >= 0; i--) {
                        if (tempData[i][1] === null || tempData[i][1] === '') {
                            tempData[i][1] = tempData[i + 1][1];
                        }
                    }
                    break;
                case 'average':
                    let sum = 0;
                    let count = 0;
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][1] !== null && tempData[i][1] !== '') {
                            sum += parseFloat(tempData[i][1]);
                            count++;
                        }
                    }
                    const average = sum / count;
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][1] === null || tempData[i][1] === '') {
                            tempData[i][1] = average;
                        }
                    }
                    break;
                case 'zero':
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][1] === null || tempData[i][1] === '') {
                            tempData[i][1] = 0;
                        }
                    }
                    break;
            }

            const values = tempData.map(row => parseFloat(row[1]));
            const dates = tempData.map(row => new Date(row[0]));
            showChart(headers[1], dates, values);
        }

        function generateCSV(data) {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Date,Value\n"; // Header
            data.forEach(row => {
                csvContent += `${row[0]},${row[1]}\n`;
            });
            return encodeURI(csvContent);
        }

        document.querySelectorAll('input[name="fill-method"]').forEach(input => {
            input.addEventListener('change', () => {
                const method = document.querySelector('input[name="fill-method"]:checked').value;
                fillMissingValues(method);
            });
        });

        document.getElementById('submit-button').addEventListener('click', () => {
            const csvData = generateCSV(tempData);

            const steps = parseInt(document.getElementById('steps').value, 10);
            const method = document.querySelector('input[name="method"]').value;
            const window_size = parseInt(document.getElementById('window_size').value, 10);
            const seasonal = document.querySelector('input[name="seasonal"]').value;


            console.log('Form submitted:', {
                steps,
                method,
                window_size,
                seasonal,
            });

            //send the data to the controller


        });
    </script>
</body>

</html> --}}



<!DOCTYPE html>
<html>

<head>
    <title>Univariate Data Processing</title>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

</head>

<body>
    <div class="container">
        <h1>Univariate Data Processing</h1>
        <div class="row">
            <div class="col-md-12">
                <div id="chart-container">
                    <canvas id="chart"></canvas>
                </div>
                <div id="processing-options">
                    <h3>Data Cleaning and Processing</h3>
                    <form id="processing-form">
                        <label>Fill Missing Value (NaN) with:</label><br>
                        <input type="radio" name="fill-method" value="forward"> Forward Fill<br>
                        <input type="radio" name="fill-method" value="backward"> Backward Fill<br>
                        <input type="radio" name="fill-method" value="average"> Average of the series<br>
                        <input type="radio" name="fill-method" value="zero"> Fill with zeros<br>
                    </form>
                </div>
            </div>
        </div>
        <button type="button" id="next-button" class="btn btn-primary" data-toggle="modal"
            data-target="#resultMethod">Next</button>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="resultMethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Options for the Result</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modalForm">
                        <div class="form-group">
                            <label for="steps">Forecasting Method</label><br>
                            maximum steps (m) to forecast:<br>

                            <input type="number" id ="steps" name="steps" required>
                            <p>How the forecast will be made? </p>
                            <input type="radio" name="method" value = "with_refit">With
                            Refit<br>
                            <input type="radio" name="method" value = "without_refit">Without
                            Refit <br>

                        </div>
                        <div class="form-group">
                            <label for="window_size">Trend Analysis Method</label>
                            {{-- we need to parse it to integer --}}
                            <p>Specify the window size: </p><br>
                            <select name="window_size" id = "window_size" class="form-control" required>
                                <option value="5">5-units window moving average</option>
                                <option value="10">10-units window moving average</option>
                                <option value="20">20-units window moving average</option>
                                <option value="30">30-units window moving average</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="seasonal">Seasonality analysis</label>
                            <p>Does the data exhibit seasonal behaviour?</p>
                            <input type="radio" name="seasonal" value = "yes">Yes <br>
                            <input type="radio" name="seasonal" value = "no">No<br>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id = "submit-button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const data = @json($data);
            const headers = @json($headers);
            let originalData = JSON.parse(JSON.stringify(data)); // Deep copy of original data
            let tempData = JSON.parse(JSON.stringify(originalData));
            let chartInstance = null; // Variable to store the current chart instance



            const chartCanvas = document.getElementById('chart');

            // Ensure that data is valid
            if (data && data.length > 0 && headers.length > 1) {
                const values = originalData.map(row => parseFloat(row[1]) || null); // Handle NaN as null
                const dates = originalData.map(row => new Date(row[0]));
                const label = headers[1];

                showChart(label, dates, values);
            } else {
                console.error('Invalid data or headers');
            }

            function showChart(label, dates, values) {
                // Destroy existing chart if it exists
                if (chartInstance) {
                    chartInstance.destroy();
                }
                const ctx = chartCanvas.getContext('2d');
                chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: [{
                            label: label,
                            data: values,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            fill: false
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'day'
                                },
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: label
                                }
                            }
                        }
                    }
                });
            }

            function fillMissingValues(method) {
                tempData = JSON.parse(JSON.stringify(originalData)); // Reset tempData to original each time

                switch (method) {
                    case 'forward':
                        for (let i = 1; i < tempData.length; i++) {
                            if (tempData[i][1] === null || tempData[i][1] === '') {
                                tempData[i][1] = tempData[i - 1][1];
                            }
                        }
                        break;
                    case 'backward':
                        for (let i = tempData.length - 2; i >= 0; i--) {
                            if (tempData[i][1] === null || tempData[i][1] === '') {
                                tempData[i][1] = tempData[i + 1][1];
                            }
                        }
                        break;
                    case 'average':
                        let sum = 0;
                        let count = 0;
                        for (let i = 0; i < tempData.length; i++) {
                            if (tempData[i][1] !== null && tempData[i][1] !== '') {
                                sum += parseFloat(tempData[i][1]);
                                count++;
                            }
                        }
                        const average = sum / count;
                        for (let i = 0; i < tempData.length; i++) {
                            if (tempData[i][1] === null || tempData[i][1] === '') {
                                tempData[i][1] = average;
                            }
                        }
                        break;
                    case 'zero':
                        for (let i = 0; i < tempData.length; i++) {
                            if (tempData[i][1] === null || tempData[i][1] === '') {
                                tempData[i][1] = 0;
                            }
                        }
                        break;
                }

                const values = tempData.map(row => parseFloat(row[1]));
                const dates = tempData.map(row => new Date(row[0]));
                showChart(headers[1], dates, values);
            }

            function generateCSV(data) {
                let csvContent = "data:text/csv;charset=utf-8,";
                csvContent += "Date,Value\n"; // Header
                data.forEach(row => {
                    csvContent += `${row[0]},${row[1]}\n`;
                });
                return encodeURI(csvContent);
            }

            document.querySelectorAll('input[name="fill-method"]').forEach(input => {
                input.addEventListener('change', () => {
                    const method = document.querySelector('input[name="fill-method"]:checked')
                        .value;
                    fillMissingValues(method);
                });
            });


            document.getElementById('submit-button').addEventListener('click', () => {
                const csvData = generateCSV(tempData);


                const steps = parseInt(document.getElementById('steps').value, 10);
                const method = document.querySelector('input[name="method"]:checked').value;
                const window_size = parseInt(document.getElementById('window_size').value, 10);
                const seasonal = document.querySelector('input[name="seasonal"]:checked').value;


                //extract the additional data from the controller. 
                const type = @json($type);
                const freq = @json($freq);
                const description = @json($description);
                const file_id = parseInt(@json($file_id), 10);

                // Create a FormData object to send the CSV and other data
                const formData = new FormData();
                formData.append('csv_file', new Blob([csvData], {
                    type: 'text/csv'
                }), 'data.csv');
                formData.append('steps', steps);
                formData.append('method', method);
                formData.append('window_size', window_size);
                formData.append('seasonal', seasonal);
                formData.append('type', type);
                formData.append('freq', freq);
                formData.append('description', description);
                formData.append('file_id', file_id);


                // Send the data using AJAX
                $.ajax({
                    url: '{{ route('ts.receive_data') }}', // URL to your Laravel route
                    type: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from automatically transforming the data into a query string
                    contentType: false, // Let the browser set the content type
                    success: function(response) {
                        console.log('Data successfully sent:', response);
                        // Handle success response
                    },
                    error: function(xhr, status, error) {
                        console.error('Error sending data:', error);
                        // Handle error response
                    }
                });
            });

        });
    </script>
</body>

</html>
