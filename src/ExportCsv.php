<?php
namespace CampaignRabbit\ExportCsv;
class ExportCsv {

    protected $export_path = '';
    protected $header = array();
    protected $resulting_csv_values = array();
    /**
     * @param $path - export csv path
     */
    public function setCsvPath($path){
        $this->export_path = $path;
    }

    /**
     * @param array $headers - csv header fields
     */
    public function setHeader($headers = array()){
        $this->headers = $headers;
    }

    /**
     * @param array $csv_values - csv values
     * @throws \Exception
     */
    public function setCsvValues($csv_values = array()){
        //validate csv value array
        if(!is_array($csv_values)){
            throw new \Exception('Array value only accepted');
        }

        if(empty($this->header)){
            throw new \Exception('Please set header first');
        }

        if(empty($csv_values)){
            throw new \Exception('Empty value not allowed');
        }

        foreach ($csv_values as $csv_value){
            $single_csv_record = array();
            foreach ($this->header as $header_key => $header_value){
                if(isset($csv_value[$header_key])){
                    if(is_array($header_value)){
                        $header_value = implode(',',$header_value);
                    }
                    array_push($single_csv_record,$header_value);
                }
            }
            $this->resulting_csv_values[] = $single_csv_record;
        }
    }

    /**
     * @throws \Exception
     */
    public function writeCsv(){
        if(empty($this->header)){
            throw new \Exception('Please set header first');
        }

        if(empty($this->resulting_csv_values)){
            throw new \Exception('Please set at least one csv record');
        }

        if(empty($this->export_path)){
            throw new \Exception('Export csv path invalid');
        }

        if(!file_exists($this->export_path) ){
            $file = fopen($this->export_path, "a");
            fputcsv($file, $this->header);
        }else{
            $file = fopen($this->export_path, "a");
        }

        foreach ($this->resulting_csv_values as $single_csv_record){
            fputcsv($file, $single_csv_record);
        }
        fclose($file);
    }
}