<?php

if (!function_exists('Answer')) {

    function validate_title($type){
        switch ($type) {
            case "success":
                $res='Listo';
            break;
            case "danger":
                $res='Error';
            break;
            case "warning":
                $res='Advertencia';
            break;
            case "info":
                $res='Informacion';
            break;
            default:
                $res='Listo';
          }
          return $res;
    }
    function validate_text($type){
        switch ($type) {
            case "success":
                $res='Acción completada';
            break;
            case "danger":
                $res='Error en acción';
            break;
            case "warning":
                $res='Precaución con esta acción';
            break;
            case "info":
                $res='Verifique la siguiente información';
            break;
            default:
                $res='Acción completada';
          }
          return $res;
    }

    function Answer($typeIn ="", $nameIn ="",$textIn ="",$titleIn =""){

            $cleanType = trim($typeIn);
            $type = (strlen($cleanType) > 0)? $typeIn:'success';

            $cleanTitle = trim($titleIn);
            $title = (strlen($cleanTitle) > 0)? $titleIn:validate_title($type);
    
            $cleanText = trim($textIn);
            $text = (strlen($cleanText) > 0)? $textIn:validate_text($type);
    
            $cleanName = trim($nameIn);
            $name = (strlen($cleanName) > 0)? $nameIn:'Item';
            
            $answer = [
                        'title'=>$title.': ',
                        'text'=>$name.' '.$text.'!',
                        'type'=>$type,
                    ];
                    
        return $answer;
    }
}