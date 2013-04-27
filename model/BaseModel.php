<?php

namespace Model;

class BaseModel {
    
    public $connection;
    public $context;
    
    public function __construct($context) {
        $this->context = $context;
        $this->connection = $context->database;
    }
    
    public function getContext() {
        return $this->context;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function formatData($data, $format) {
        $output = array();
        
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, array_keys($format))) {
                    if ($format[$key] == '%d' && empty($value)) {
                        $output[$key] = $value;
                    } else {
                        $output[$key.$format[$key]] = $value;
                    }
                } else {
                    $output[$key] = $value;    
                }
            }
            
        }
    
        return $output;
    }
    
    
    /**
     * Generates unique url chunk for given table and id
     * Assumes url in following format:
     *  /url_chunk[-counter]
     * 
     * @param type $chunk
     * @param type $id
     * @param type $table 
     */
    public function generateUniqueUrlChunk($chunk, $id, $tableName, $idAttribName) {
        
        // does this chunk already exist in table ?
        $c = $this->connection->fetchSingle('SELECT COUNT(*) FROM '.$tableName.' WHERE [url_chunk] = %s AND '.$idAttribName.' != %i', $chunk, $id);
        
        if ($c == 0) {
            // chunk is unique and we are done
            return $chunk;
        } else {
            // this chunk is already in use
            $allChunks = $this->connection->query('SELECT [url_chunk], '.$idAttribName.' FROM '.$tableName.' WHERE [url_chunk] LIKE %~like~ AND '.$idAttribName.' != %i', $chunk, $id)->fetchPairs($idAttribName, 'url_chunk');
            
            // parse all the chunks and try to get the last value of the counter
            $counter = 1;
            foreach ($allChunks as $id => $_chunk) {
                if (preg_match('#'.$chunk.'-([0-9])#', $_chunk, $matches)) {
                    $counter = max($counter, $matches[1]);
                }
            }
            
            return $chunk.'-'.($counter+1);
        }
        
        
    }
    
}