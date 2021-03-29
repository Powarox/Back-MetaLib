<?php

namespace Metadata\Forms;

class CreateSimpleMetaForm {
    protected $form;

    public function __construct($form = ''){
        $this->form = $form;
    }

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
        $this->form = '
            <form action="'.$formAction.'" methode="'.$formMethode.'">
            <ul>';

        foreach($metaByType as $key => $value){
            $this->form .=
                '<li>
                <strong>'.$key.'</strong>';

            if(is_array($value)){
                $this->form .= '<div>';
                foreach($value as $k => $v){
                    if(strlen($v) > 30){
                        $this->form .= '<textarea name="'.$key.'[]" rows="8">'.$v.'</textarea>';
                    }
                    else {
                        $this->form .= '<input type="text" name="'.$key.'[]" value="'.$v.'">';
                    }
                }
                $this->form .= '</div>';
            }
            else {
                if(strlen($value) > 30){
                    $this->form .= '<textarea name="'.$key.'" rows="8">'.$value.'</textarea>';
                }
                else {
                    $this->form .= '<input type="text" name="'.$key.'" value="'.$value.'">';
                }
            }
            $this->form .= '
                </li>
                </ul>';
        }

        $this->form .= '
            <input type="submit" value="Send">
            </form>';

        return $this->form;
    }
}
