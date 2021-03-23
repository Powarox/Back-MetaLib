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



// ########## ------------- Modification ------------- ########## //
    /**
     * Transforme un array trié par type, en un array non trié
     *
     * @param Array $meta : contient des métadonnées trié par type
     * @return Array $metaTransform : contient les métadonnées non trié
    */
    public function transformMetaArray($meta){
        $metaTransform = [];
        foreach($meta as $key => $value){
            if(is_array($value)){
                foreach ($value as $k => $v) {
                    $metaTransform[$k] = $v;
                }
            }
            else{
                $metaTransform[$key] = $value;
            }
        }
        return $metaTransform;
    }

    /**
     * Modifie les métadonnées d'un fichier à partir d'un fichier json
     *
     * @param String $jsonFilePath : localisation du fichier json contenant les
     *      nouvelles métadonnées dir/file.json
     * @param String $filePath : localisation du fichier initial dir/file.ext
    */
    public function importNewMetaFromJsonFile($jsonFilePath, $filePath){
        shell_exec("exiftool -json=$jsonFilePath $filePath");
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



// ########## ------------- Sorted Array ------------- ########## //
    /**
     * Trie un tableau par longueur des des valeur(array)
     *
     * @param Array $meta : Tableau de Tableau
     * @param Array $meta : Tableau de Tableau trié
    */
    public function sortedArrayByValue($meta){
        asort($meta);
        return $meta;
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
