<?php

namespace Metadata;

use Metadata\Tools\Utilitaire;
use Metadata\Tools\GestionErrors;
use Metadata\Tools\GestionExiftool;

class Metadata {
    protected $errors;
    protected $exiftool;
    protected $utilitaire;

    public function __construct(){
        $this->errors = new GestionErrors();
        $this->utilitaire = new Utilitaire();
        $this->exiftool = new GestionExiftool();
    }


// ########## ------------- Extraction ------------- ########## //
    /**
     * Extrait les métadonnées d'un fichier
     *
     * @param String $filePath : localisation du fichier dossier/file.extension
     * @return Array $metaData : contient les métadonnées du fichier d'entré
    */
    public function getMeta($filePath){
        $data = shell_exec("exiftool -json ".$filePath);
        $metaData = json_decode($data, true);
        return $metaData[0];
    }


    /**
     * Extrait les métadonnées d'un fichier en les triant par type
     *
     * @param String $filePath : localisation du fichier dossier/file.extension
     * @return Array $metaData : contient les métadonnées du fichier d'entré
    */
    public function getMetaByType($filePath){
        $data = shell_exec("exiftool -g -json ".$filePath);
        $metaData = json_decode($data, true);
        return $metaData[0];
    }


    /**
     * Extrait tous les type de métadonnées différents
     *
     * @param String $filePath : localisation du fichier dossier/file.extension
     * @return Array $metaData : contient les clefs des métadonnées du fichier
    */
    public function getMetaKeysType($filePath){
        $data = shell_exec("exiftool -g -json ".$filePath);
        $metaData = json_decode($data, true);
        return array_keys($metaData);
    }




// ########## ------------- Sauvegarde ------------- ########## //
    /**
     * Ouvre un fichier Json pour extraire les données
     *
     * @param String $dirFile : localisation du fichier dossier/file.extension
     * @param Array $data : contient les données extraites
    */
    public function openMetaOnJsonFile($filePath){
        $jsonData = file_get_contents($filePath);
        $data = json_decode($jsonData, true);
        return $data;
    }


    /**
     * Sauvegarde un array dans un fichier json
     *
     * @param String $folder : nom du dossier de sortie dir/dir/
     * @param String $name : nom du fichier de sortie sans extension
     * @param Array $meta : array contenant les métadonnées à sauvegarder
     * @return String $filepath : chemin du fichier json contenant les méta
    */
    public function saveMetaJsonFile($folder, $name, $meta){
        $data = json_encode($meta);
        $filepath = $folder.$name.'.json';
        $metaTxt = fopen($filepath, 'w');
        fputs($metaTxt, $data);
        fclose($metaTxt);
        return $filepath;
    }




// ########## ------------- Autre ------------- ########## //
    /**
     * Télécharge un fichier
     *
     * @param String $filePath : nom du dossier de sortie dir/dir/file.png
    */
    public function downloadFile($filePath){
        $this->utilitaire->downloadFile($filePath);
    }


}
