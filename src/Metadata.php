<?php

namespace Metadata;

class Metadata {

    public function __construct(){

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
     * Trouve des occurence d'un motif dans les clef d'un tableau de données
     *
     * @param Array $array : tableau associatif contenant les données
     * @param String $motif : motif a rechercher avec preg_match()
     * @return Array $arrayMetaType : données associée au motif trouvé
    */
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
