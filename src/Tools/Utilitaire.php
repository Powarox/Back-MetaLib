<?php

namespace Metadata\Tools;

class Utilitaire {

    /**
     * Lance le téléchargement d'un ficher
     *
     * @param String $file : localisation du fichier dossier/file.extension
    */
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

    /**
     * Créer une image à partir d'un pdf
     *
     * @param String $filePath : localisation du fichier dossier/file.extension
     * @param String $folder : répertoire cible pour enregistrement
     * @param String $name : nom fichier souhaité
    */
    public function createPdfImage($filePath, $folder, $name){
        exec('convert '.$filePath.'[0]  '.$folder.$name.'.jpg');
    }

    /**
     * Supprime tous les fichiers d'un dossier
     *
     * @param String $folder : Dossier cible pour la suppressions des fichiers
    */
    public function cleanFolder($folder){
        unlink(__DIR__.'//Files/'.$f);
    }

    /**
     * Supprime tous les fichiers d'un dossier
     *
     * @param String $folder : Dossier cible /dir/dir/
     * @return Array $result : contient le nom de tous les fichier présents
    */
    public function getFileNameInFolder($folder){
        $files = scandir(__DIR__ .$folder);
        if (!empty($files)) {
            $elemAutre = array_shift($files);
            $elemAutre = array_shift($files);
        }
        return $result;
    }

    /**
     * Retourne le chemin d'un fichier
     *
     * @param String $folder : Dossier cible /dir/dir/
     * @return Array $result : contient le nom de tous les fichier présents
    */
    public function getPathFile(){

    }
}
