# Librairie PHP métadonnées



## Installation librairie
- Prérequis : "php": ">=5.3.0"

- Dernière version : v2.0.0

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


## Extraction

```php
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
```

```php
/**
 * Extrait les métadonnées d'un fichier en les triant par type
 *
 * @param String $filePath : localisation du fichier dossier/file.extension
 * @return Array $metaData : contient les métadonnées du fichier d'entré
*/
public function getMetaByType($filePath){
    $data = shell_exec("exiftool -g1 -json ".$filePath);
    $metaData = json_decode($data, true);
    return $metaData[0];
}
```

```php
/**
 * Extrait tous les type de métadonnées différents
 *
 * @param String $filePath : localisation du fichier dossier/file.extension
 * @return Array $metaData : contient les clefs des métadonnées du fichier
*/
public function getMetaKeysType($filePath){
    $data = shell_exec("exiftool -g1 -json ".$filePath);
    $metaData = json_decode($data, true);
    return array_keys($metaData);
}
```

## Modifications

```php
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
```

```php
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
```

## Sauvegarde

```php
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
```

```php
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
```

## Utilitaire

```php
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
```

```php
/**
 * Télécharge un fichier
 *
 * @param String $filePath : nom du dossier de sortie dir/dir/file.png
*/
public function downloadFile($filePath){
    $this->utilitaire->downloadFile($filePath);
}
```

## Multiple Actions

```php
/**
 * Extrait les métadonnées d'un fichier puis les sauvegarde dans un fichier
 * json
 *
 * @param String $filePath : localisation du fichier dossier/file.extension
 * @param String $folder : nom du dossier de sortie dir/dir/
 * @param String $name : nom du fichier de sortie sans extension
*/
public function extractAndSaveMeta($filePath, $folder, $name){
    $meta = $this->getMeta($filePath);
    $this->saveMetaJsonFile($folder, $name, $meta);
}
```

```php
/**
 * Extrait les métadonnées d'un fichier, les sauvegarde dans un fichier json
 * puis télécharge ce fichier json
 *
 * @param String $filePath : localisation du fichier dossier/file.extension
 * @param String $folder : nom du dossier de sortie dir/dir/
 * @param String $name : nom du fichier de sortie sans extension
*/
public function extractSaveAndDownloadMeta($filePath, $folder, $name){
    $meta = $this->getMeta($filePath);
    $jsonPath = $this->saveMetaJsonFile($folder, $name, $meta);
    $this->utilitaire->downloadFile($jsonPath);
}
```

```php
/**
 * Extrait les métadonnées d'un fichier en les triant par type puis les
 * sauvegarde dans un fichier json
 *
 * @param String $filePath : localisation du fichier dossier/file.extension
 * @param String $folder : nom du dossier de sortie dir/dir/
 * @param String $name : nom du fichier de sortie sans extension
*/
public function extractByTypeAndSaveMeta($filePath, $folder, $name){
    $metaByType = $this->getMetaByType($filePath);
    $this->saveMetaJsonFile($folder, $name, $metaByType);
}
```

```php
/**
 * Extrait les métadonnées d'un fichier en les triant par type, les
 * sauvegarde dans un fichier json puis télécharge ce fichier json
 *
 * @param String $filePath : localisation du fichier dossier/file.extension
 * @param String $folder : nom du dossier de sortie dir/dir/
 * @param String $name : nom du fichier de sortie sans extension
*/
public function extractByTypeSaveAndDownloadMeta($filePath, $folder, $name){
    $metaByType = $this->getMetaByType($filePath);
    $jsonPath = $this->saveMetaJsonFile($folder, $name, $metaByType);
    $this->utilitaire->downloadFile($jsonPath);
}
```

```php
/**
 * Transforme un array trié par type, en un array non trié, Sauvegarde les
 * les métadonnées dans un fichier json puis modifie un fichier à partir
 * des métatransformés
 *
 * @param String $jsonFilePath : localisation du fichier json contenant les
 * nouvelles métadonnées dir/file.json
 * @param String $folder : nom du dossier de sortie dir/dir/
 * @param String $name : nom du fichier de sortie sans extension
 * @param Array $meta : contient des métadonnées trié par type
*/
public function transformSaveAndImportMeta($filePath, $folder, $name, $meta){
    $metaTransform = $this->transformMetaArray($meta);
    $jsonPath = $this->saveMetaJsonFile($folder, $name, $metaTransform);
    $this->importNewMetaFromJsonFile($filePath, $jsonPath);
}
```

```php
/**
 * Transforme un array trié par type, en un array non trié, Sauvegarde les
 * les métadonnées dans un fichier json, modifie un fichier à partir des
 * métatransformés puis télécharge le fichier modifié
 *
 * @param String $jsonFilePath : localisation du fichier json contenant les
 * nouvelles métadonnées dir/file.json
 * @param String $folder : nom du dossier de sortie dir/dir/
 * @param String $name : nom du fichier de sortie sans extension
 * @param Array $meta : contient des métadonnées trié par type
*/
public function transformSaveImportAndDownloadMeta($filePath, $folder, $name, $meta){
    $metaTransform = $this->transformMetaArray($meta);
    $jsonPath = $this->saveMetaJsonFile($folder, $name, $metaTransform);
    $this->importNewMetaFromJsonFile($filePath, $jsonPath);
    $this->utilitaire->downloadFile($filePath);
}
```

```php
/**
 * Sauvegarde les les métadonnées dans un fichier json puis modifie un fichier
 * à partir des métadonnées
 *
 * @param String $jsonFilePath : localisation du fichier json contenant les
 * nouvelles métadonnées dir/file.json
 * @param String $folder : nom du dossier de sortie dir/dir/
 * @param String $name : nom du fichier de sortie sans extension
 * @param Array $meta : contient des métadonnées trié par type
*/
public function saveAndImportMeta($filePath, $folder, $name, $meta){
    $jsonPath = $this->saveMetaJsonFile($folder, $name, $meta);
    $this->importNewMetaFromJsonFile($filePath, $jsonPath);
}
```

```php
/**
 * Sauvegarde les les métadonnées dans un fichier json, modifie un fichier
 * à partir des métadonnées puis télécharge le fichier modifié
 *
 * @param String $jsonFilePath : localisation du fichier json contenant les
 * nouvelles métadonnées dir/file.json
 * @param String $folder : nom du dossier de sortie dir/dir/
 * @param String $name : nom du fichier de sortie sans extension
 * @param Array $meta : contient des métadonnées trié par type
*/
public function saveImportAndDownloadMeta($filePath, $folder, $name, $meta){
    $jsonPath = $this->saveMetaJsonFile($folder, $name, $meta);
    $this->importNewMetaFromJsonFile($filePath, $jsonPath);
    $this->utilitaire->downloadFile($filePath);
}
```

## Création Formulaire

```php
/**
 * Créer un formulaire HTML à partir de métadonnées trié par type
 *
 * Param obligatoires
 * @param Array $metaByType : donnée pour construire le form
 * @param String $formAction : action à effectuer après envoie (index.php?...)
 * @param String $formMethode : methode d'envoie (get, post, ...)
 *
 * Param optionnels
 * @param Array $formClass : ajouter une class sur le from
 * @param String $formId : ajouter un id sur le form
 * @param String $divClass : ajouter une class pour les div
 * @param Array $submitId : ajouter un id sur bouton submit
 * @param String $name : ajouter une value sur bounton submit
 *
 * @return String $this->form : formulaire HTML au format de string
*/
public function createMetaFormByType($metaByType, $formAction, $formMethode,
    $formClass = '', $formId = '',  $divClass = '', $submitId = '', $name = 'valider'){
    $this->simpleForm->createMetaFormByType($metaByType, $formAction, $formMethode,
            $formClass, $formId,  $divClass, $submitId, $name);
}
```

```php
/**
* Créer un formulaire HTML à partir de métadonnées
 *
 * Param obligatoires
 * @param Array $metaByType : donnée pour construire le form
 * @param String $formAction : action à effectuer après envoie (index.php?...)
 * @param String $formMethode : methode d'envoie (get, post, ...)
 *
 * @return String $this->form : formulaire HTML au format string
*/
public function createForm($metaByType, $formAction, $formMethode){
    $this->metaFormByType->createForm($metaByType, $formAction, $formMethode);
}
```
