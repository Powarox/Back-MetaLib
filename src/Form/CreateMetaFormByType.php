<?php

namespace Metadata\Forms;

class CreateMetaFormByType {
    protected $form;

    public function __construct($form = ''){
        $this->form = $form;
    }

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
            $this->initForm($formClass, $formId, $formAction, $formMethode);
            $this->prepareFormByType($metaByType, $divClass);
            $this->finishForm($submitId, $name);
            return $this->form;
    }

    public function initForm($formClass, $formId, $formAction, $formMethode){
        $this->form = '<form class="'.$formClass.'" id="'.$formId.'" action="'.$formAction.'" methode="'.$formMethode.'">';
    }

    public function prepareFormByType($metaByType, $divClass){
        foreach($metaByType as $key => $value){
            $this->form .= '
                <div class="'.$divClass.'" id="'.$key.'_box">
                <h2>'.$key.'</h2>
                <ul>';
            if(is_array($value)){
                foreach($value as $k => $v){
                    $this->form .= '<li>';
                    $this->form .= '<p><strong>'.$k.'</strong></p>';
                    if(is_array($v)){
                        $this->form .= '<div>';
                        foreach ($v as $newValue) {
                            $this->form .= '<input type="text" name="'.$key.'['.$k.'][]" value="'.$newValue.'">';
                        }
                        $this->form .= '</div>';
                    }
                    else {
                        if(strlen($v) > 30){
                            $this->form .= '<textarea name="'.$key.'['.$k.']" rows="8">'.$v.'</textarea>';
                        }
                        else {
                            $this->form .= '<input type="text" name="'.$key.'['.$k.']" value="'.$v.'">';
                        }
                    }
                    $this->form .= '</li>';
                }
            }
            else {
                $this->form .= '
                    <li id="elemP">
                    <p><strong>'.$key.'</strong></p>
                    <input type="text" name="'.$key.'" value="'.$value.'">
                    </li>';
            }

            $this->form .= '
                </ul>
                </div>';
        }
    }

    public function finishForm($submitId, $name){
        $this->form .= '
            <input id="'.$submitId.'" type="submit" value="'.$name.'">
            </form>';
    }
}
