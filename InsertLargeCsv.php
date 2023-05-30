public function insert_large_csv()
{
    // Below lines are optional so please uncomment if require
    // ini_set('max_execution_time', 1000);
    // ini_set('memory_limit', '100M');
    try {
        
        $csv_file_path = 'full-path-of-csv'; // Ex. ../../demo.csv

        // Open the CSV file
        $file = fopen($csv_file_path, 'r');

        if(!empty($file)) {
            // Skip the header row
            $header = fgetcsv($file);

            // Define the batch size for chunk processing
            $batchSize = 100;

            $batch_number = 1;
            // Read the file in chunks and insert records
            while (($data = fgetcsv($file)) !== false) {
                $records = [];

                if(!empty($data)) {
                    // Create an array of records for the current chunk
                    for ($i = 0; $i < $batchSize; $i++) {
                        if ($data !== false) {
                            $records[] = [
                                'col1'    => !empty($data[0]) ? $data[0] : NULL,
                                'col2'       => !empty($data[1]) ? $data[1] : NULL,
                                // Add More Row Here If You Want
                            ];
                            $data = fgetcsv($file);
                        } else {
                            break;
                        }
                    }

                    if(!empty($records)) {
                        $result = DB::table('table-name')->insert($records);
                        $batch_number++;
                        unset($records);
                    } else {
                        Log::notice("Record Not Added for $batch_number");
                        $batch_number++;
                        echo 'record not found!';
                    }
                } else {
                    echo "Data not found!";
                }

            }

            // Close the file
            fclose($file);
        } else {
            echo "Not able to read file";
        }


    } catch (Exception $ex ) {
        dd($ex);
    }
}
