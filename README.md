# Librairie PHP métadonnées



## Installation librairie
- Prérequis : "php": ">=5.3.0"

- Dernière version : 1.3.0

- Avec Composer :
    - composer require robindev/metadata

- Modifier la version :
    - Ouvrir composer.json
    - Modifier le numéro de version

    ```json
    {
        "require": {
            "robindev/metadata": "^1.3.0"
        }
    }
    ```

## Utilisation librairie
- Initialisation :

    ```php
    <?php

        namespace App;

        require './vendor/autoload.php';

        $lib = new \Metadata\Metadata();
    ```

- Appel de fonctions :

    ```php
    <?php

        $result = $lib->functionExemple($param1, $param2);
    ```


- Fonctions disponibles :
    - getMeta($file) permet d'extraire les métadonnées d'un fichier pdf puis de les retourner sous forme de tableau.

    ```php
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
    ```

    - Sortie sous la forme :
        ```json
            array (size=31)
              'SourceFile' => string 'App/Files/all-document3.pdf' (length=27)
              'ExifToolVersion' => float 10.4
              'FileName' => string 'all-document3.pdf' (length=17)
              'Directory' => string 'App/Files' (length=9)
        ```



    - getMetaByType($meta) permet de trier les métadonnées par catégorie (xmp, file, exif, ... d'autre à venir)

    ```php
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
    ```

    - Sortie sous la forme :
        ```json
            array (size=31)
              'file' => array(
                  'SourceFile' => string 'App/Files/all-document3.pdf' (length=27)
                  'FileName' => string 'all-document3.pdf' (length=17)
              ),
              'xmp' => array(
                  'XMPToolkit' => string 'Image::ExifTool 12.00' (length=21)
              )
        ```


    - openMetaOnJsonFile($dirFile) permet d'ouvrir un fichier json contenant des métadonnées, puis de les extraitre pour retourner celle-ci sous forme de tableau. Nécessite de connaître de la localisation du fichier json.

    ```php
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
    ```


    - saveMetaJsonFile($folder, $name, $meta) permet de sauvegarder des métadonnées dans un fichier json.
    ```php
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
    ```
