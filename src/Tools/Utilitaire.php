<?php

namespace Metadata\Tools;

class Utilitaire {

    public function __construct(){

    }

    public function downloadFile($file){
        if(file_exists($file)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
    }
}

// try {
//
// } catch(e) {
//
// } finally {
//
// }
