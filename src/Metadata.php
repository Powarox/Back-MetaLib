<?php

namespace Metadata;

class Metadata {
    protected $file;
    protected $meta;
    protected $typeOfFile;

// Constructor
    public function __construct(){  // $file
        // $this->file = '$file';
        // $this->meta = $this->meta($this->file);
        // $this->typeOfFile = "";     // Pdf, Jpg, Png, ...
    }

    // Extrait l'extension du fichier a traiter
    public function extractTypeOfFile($meta){
        $filename = $meta['FileName'];
        $tab = explode('.', $filename);
        $this->typeOfFile = $tab[1];
    }

    // Type potentielle de meta en fonction du fichier
    public function test(){
        $potential = array(
            "pdf" => array("PDF", "XMP", "..."),
            "jpg" => array("XMP", "IPTC", "geoloc", "..."),
            "png" => array("XMP", "IPTC", "geoloc", "...")
        );
    }

    // Trouve occurence d'un type dans tableau metaType xmp, file, ...
    // occurence XMP... | ...XMP
    // "/@?(".$motif.")/im" : occurence n'importe ou dans string
    public function regex($array, $motif){
        $pattern = "/@?^(".$motif.")|@?(".$motif.")$/im";
        $metaTypeOf = [];

        foreach($array as $key => $value){
            if(preg_match($pattern, $key)){
                $metaTypeOf[$key] = $value;
            }
        }
        return $metaTypeOf;
    }


    /**
     * Extrait les métadonnées d'un fichier
     *
     * @param String $file : localisation du fichier dossier/file.extension
     * @return Array $metaData : contient les métadonnées du fichier d'entré
    */
    public function getMeta($file){
        $data = shell_exec("exiftool -json ".$file);
        $metaData = json_decode($data, true);
        return $metaData[0];
    }


    /**
     * Extrait les types de métadonnées d'un fichier
     *
     * @param Array $meta : tableau de métadonnées
     * @return Array $typeMeta : tableau contenant les types de métadonnées
    */
    public function getMetadataType($meta){
        $typeMeta = array_keys($meta);
        return $typeMeta;
    }


    /**
     * Extrait les métadonnées d'un certain type
     *
     * @param Array $meta : localisation du fichier dossier/file.extension
     * @param String $type : type de métadonnées souhaité : EXIF, XMP, FILE, ...
     * @return Array $metaTypeOf : contient les métadonnées du type $type
    */
    public function getMetaOfType($meta, $type){
        $metaTypeOf = [];
        foreach($meta as $key => $value){
            if($key === $type){
                echo 'je suis dedans';
            }
        }
        return $meta;
    }


    /**
     * Trie les métadonnées par type
     *
     * @param Array $meta : tableau contenant les métadonnées
     * @param String $file : localisation du fichier dossier/file.extension
     * @return Array $arrayMetaType : métadonnées triées par type
    */
    public function getMetaByType($meta){
        // Warning need moyen de classer les types
        $type = array('file', 'xmp');
        $arrayMetaType = [];

        foreach($type as $key){
            $arrayMetaType[$key] = $this->regex($meta, $key);
            $meta = array_diff_key($meta, $arrayMetaType[$key]);
        }
        $arrayMetaType['other'] = $meta;

        return $arrayMetaType;
    }


    /**
     * Ajoute des métadonnées dans un fichier : pdf, jpg, png, ...
     *
     * @param String $file : localisation du fichier dossier/file.extension
     * @param Array $metaData : contient les métadonnées à ajouter
    */
    public function setMeta($file, $meta){

    }


    /**
     * Ouvre un fichier Json pour extraire les données
     *
     * @param String $dirFile : localisation du fichier dossier/file.extension
     * @param Array $data : contient les données extraites
    */
    public function openMetaOnJsonFile($dirFile){
        $jsonData = file_get_contents($dirFile);
        $data = json_decode($jsonData, true);
        return $data;
    }


    /**
     * Trie un tableau de métadonnées
     *
     * @param Array $meta : localisation du fichier dossier/file.extension
     * @return Array $meta : métadonnées triées par ...
    */
    public function sortMetaByKey($meta){
        ksort($meta);
        return $meta;
    }


    /**
     * Supprime les métadonnées redondantes
     *
     * @param Array $meta : contient les métadonnées à netoyer
     * @return Array $metaData : contient les métadonnées dépourvu de doublons
    */
    public function suppressMetaDouble($meta){
        $metaClean = [];
        foreach($meta as $key => $value){
            if(!key_exists($key, $metaClean)){
                $metaClean[$key] = $value;
            }
            else if($metaClean[$key] != $value){
                $metaClean[$key] = $value;
            }
        }
        return array_unique($meta);
    }


    /**
     * Extrait Modifie les méta d'un fichier
     *
     * @param String
     * @return Array
    */
    public function setMetaModify(){

    }


    /**
     * Modifie les méta d'un fichier json
     *
     * @param String $folder : nom du dossier de sortie dir/dir/
     * @param String $name : nom du fichier de sortie sans extension
     * @param Array $meta : array contenant les métadonnées à sauvegarder
    */
    public function modifyMetaJsonFile($foler, $name, $meta){
        $this->saveMetaJsonFile($foler, $name, $meta);
    }


    /**
     * Sauvegarde un array dans un fichier json
     *
     * @param String $folder : nom du dossier de sortie dir/dir/
     * @param String $name : nom du fichier de sortie sans extension
     * @param Array $meta : array contenant les métadonnées à sauvegarder
    */
    public function saveMetaJsonFile($folder, $name, $meta){
        $data = json_encode($meta);
        $metaTxt = fopen($folder.$name.'.json', 'w');
        fputs($metaTxt, $data);
        fclose($metaTxt);
    }


    /**
     * Put meta dans un fichier json
     *
     * @param String
     * @return Array
    */
    public function putMetaJsonFile($file, $meta){

    }


    /**
     * Clean les métadonnées selon les normes
     *
     * @param String
     * @return Array
    */
    public function cleanMeta($meta){

    }


// Gestion des erreurs
    public function getErr1(){
        throw new \Exception("Error ... Message : Ce type de fichier n'est pas pris en charge", 1);
    }

    public function getErr2(){
        throw new \Exception("Error ... Message", 1);
    }

    public function getErr3(){
        throw new \Exception("Error ... Message", 1);
    }
}

// try {
//
// } catch(e) {
//
// } finally {
//
// }
