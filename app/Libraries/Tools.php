<?php

namespace App\Libraries;

use \DateTime;

class Tools{
    
    public static $_valores = []; 

    static $_trads=["es"=>[
        "m.01"=>"enero",
        "m.02"=>"febrero",
        "m.03"=>"marzo",
        "m.04"=>"abril",
        "m.05"=>"mayo",
        "m.06"=>"junio",
        "m.07"=>"julio",
        "m.08"=>"agosto",
        "m.09"=>"septiembre",
        "m.10"=>"octubre",
        "m.11"=>"noviembre",
        "m.12"=>"diciembre"
       
        ]];

    public static $_lang = "es";
    
    /**
     * tools::catalogo()
     * 
     * @param array $array
     * @param string $id_name
     * @param string $value_name
     * @return void
     */
    public static function catalogo($array,$id_col,$value_col,$head=false){
    $out = [];
        if($head!==false){
        $out[""]=strval($head);
        }
        foreach($array as $arr){
        $out[$arr[$id_col]]=$arr[$value_col];
        }    
    return $out;
    }
    
    

    
    
    
    /**
     * tools::valores()
     * 
     * @param mixed $arr
     * @return void
     */
    public static function valores($arr=[]){
    self::$_valores = $arr;
    } 
    /**
     * tools::valor()
     * 
     * @param mixed $key
     * @param string $default
     * @return string
     */
    public static function valor($key,$default=""){
    $v = $default;
        if(isset(self::$_valores[$key])){$v=self::$_valores[$key];}
    return $v;
    }
    
    
    
    /**
     * tools::limpia()
     * 
     * @param mixed $key
     * @param mixed $filter
     * @param integer $maxLength
     * @return
     */
    public static function limpia($string,  $filter, int $maxLength=0) : string  {
    $val = filter_var($string,$filter);
    $val = is_string($val)?$val:"";

        if($maxLength > 0 && strlen($val) > 0){$val = substr($val,0,$maxLength);}

    return $val;
    }

    public static function _string($string, int $maxLength=0) : string{
        $val = filter_var($string,FILTER_SANITIZE_ADD_SLASHES);//FILTER_SANITIZE_ENCODED,FILTER_FLAG_ENCODE_HIGH
        $val = is_string($val)?$val:"";
    
            if($maxLength > 0 && strlen($val) > 0){$val = substr($val,0,$maxLength);}
    
        return $val;
    }
    public static function _int( $string) : int{
        return (int)self::limpia($string,FILTER_SANITIZE_NUMBER_INT);
    }
    public static function _float( $string) : float{
        return (float)self::limpia($string,FILTER_SANITIZE_NUMBER_FLOAT);
    }
    public static function _email( $string) : string{
        return (float)self::limpia($string,FILTER_SANITIZE_EMAIL,90);
    }


    
    /**
     * tools::fechaIso()
     * 
     * @param string $string dd/mm/YYYY
     * @return string
     */
    public static function fechaIso($string=""){
        if(strlen($string) < 8 OR strlen($string) > 11 ){return false;}
    $fa = explode("/",$string);
        if(count($fa)!=3){return false;}
        
    $nf = $fa[2]."-".$fa[1]."-".$fa[0];
    return $nf;
    }
    
    
    /**
     * tools::fechaLatin()
     * 
     * @param string $string
     * @return string
     */
    public static function fechaLatin($string=""){
        if(empty($string) || $string=="0000-00-00"){return "";}
    $f = new DateTime($string);
    return $f->format("d/m/Y");    
    }
    
    
    /**
     * tools::fechaLargo()
     * 
     * @param string $string
     * @return
     */
    public static function fechaLargo($string="",$hora=false,$anio_flexible=false){
    $f = new DateTime($string);
    $d = $f->format("d");
    $m = $f->format("m");
    $Y = $f->format("Y");
    $w = $f->format("w");

    $fFechaLargo="%d de %m de %Y %H:%i";
    $dsem=["1"=>"L","2"=>"M","3"=>"Mi","4"=>"J","5"=>"V","6"=>"S","7"=>"D"];

    $vars = ["%w","%d","%m","%Y"];
    $rep = [$dsem[$w],$d, $dsem[$m],$Y];
        if($anio_flexible == true){
            if($Y != date("Y")){
            $str = str_replace($vars,$rep,$fFechaLargo);      
            }
            else{
            $str = str_replace($vars,$rep, $fFechaLargo);      
            }          
        }
        else{
        $str = str_replace($vars,$rep, $fFechaLargo);    
        }    
    $str.=($hora==true)?" ".$f->format("H:i"):"";
    
    return $str;    
    }


    public static function fechaMedioLargo($string="",$hora=false){
    $f = new DateTime($string);
    $d = $f->format("d");
    $m = $f->format("m");
    $Y = $f->format("Y");
    $w = $f->format("w");

    $vars = ["%d","%m","%Y"];
    $rep = [$d,self::$_trads[self::$_lang]["m.".$m],$Y];
    $str = str_replace($vars,$rep,"%d de %m, %Y");
    $str.=($hora==true)?" ".$f->format("H:i"):"";
    
    return $str;    
    }    
    
    

    
    
    /**
     * tools::numerosAOl()
     *  texto que contiene 1.numeros 2. asi
     * @param string $txtoriginal
     * @return
     */
    public static function numerosAOl($txtoriginal="",$solo_li=true){
    $toarr = explode(".",$txtoriginal);
    $str="";
            foreach($toarr as $t){                
                if(strlen($t)<5){continue;}
            $s=str_replace("-","",$t);
            //$s = preg_replace('/^[0-9]+$/','',$s,3);
            $s = preg_replace('/[0-9]/','',$s,3);
            $s = trim($s);
            $str.="<li>".$s.".</li>";
            }
            if($solo_li==true){
            return $str;    
            }
    return "<ol>".$str."</ol>";  
    }


    public static function txtNumerosAArray($txtoriginal=""){
    $toarr = preg_split('/[0-9]+.-/',$txtoriginal);    
        if(count($toarr)<2){
        $toarr = preg_split('/[0-9]-/',$txtoriginal);
        }

    $arr=[];
            foreach($toarr as $t){                
                if(strlen($t)<5){continue;}
            //$s=str_replace("-","",$t);
            //$s = preg_replace('/[0-9]/','',$s,3);
            $s = trim($t);
            $s=trim($s);
            $arr[]=$s;
            }
    return $arr;  
    }   
    
    
    public static function obtener_valoracion($cual, $valoraciones, $paciente=[]){
    $r="NV";
    $enmeses = !empty($paciente)?$paciente["enmeses"]:1200;
    $enmeses = empty($enmeses)?1200:$enmeses;
    
        foreach($valoraciones as $va){
            if($va["fonema"]==$cual && $va["valorado"]==1){$r="L";break;}
            elseif($va["fonema"]==$cual && $va["valorado"]==="0"){$r="N";break;}
            elseif($va["fonema"]==$cual && $va["valorado"]==-1){$r="NV";break;}
            elseif($va["fonema"]==$cual && $va["valorado"]==null && ($va["meses_max"]<$paciente["enmeses"])){$r="L";break;}
            elseif($va["fonema"]==$cual && $va["valorado"]==null && ($va["meses_min"]>$paciente["enmeses"])){$r="E";break;}  
            elseif($va["fonema"]==$cual && $va["valorado"]==null){$r="NV";break;}
        }
    return $r;
    } 
    
    
    
    /**
     * tools::pdf_img()
     * 
     * @param mixed $path desde Base
     * @param string $extraParams usar comillas normales
     * @return
     */
    public static function pdf_img($path, $extraParams=""){
    $h="";
        if(empty($path)){return $h;}

    $imgpath=base_path().$path;
    
        if(is_file($imgpath)){
        $imgdata = file_get_contents($imgpath);
        $b64data = base64_encode($imgdata);
     
        $h= '<img  src="data:image/jpg;base64,'.$b64data.'" '.$extraParams.' />';     
        //$h.= "<img src='".$imgpath."' />";     
        }
    return $h;
    }
    
    
    
    /**
     * tools::array_val()
     * Val de elemento en array, si existe
     * @param mixed $array
     * @param mixed $index
     * @param bool $default
     * @return mixed
     */
    public static function array_val($array,$index,$default=false){
        return isset($array[$index])?$array[$index]:$default;
    }
    
    
    
    /**
     * tools::hora12()
     * 
     * @param mixed $string
     * @return
     */
    public static function hora12(string $string,$padded=false){
    $ob = new DateTime($string);    
    $hora = intval($ob->format("H"));    
    $mStr = ($hora>11)?"PM":"AM";    
        if($padded){
        $str = ($ob->format("i")==30)?$ob->format("h:i"):$ob->format("h").":00";    
        }
        else{
        $str = ($ob->format("i")==30)?$ob->format("h:i"):$ob->format("h");    
        }
    
    return $str." ".$mStr;    
    }
    
    
    public static function uri_set_var(string $uri, string $key="", string $val=""){
    $pts = parse_url($uri);
    $qs = [];
    parse_str($pts["query"],$qs);
        if(array_key_exists($key,$qs)){
        unset($qs[$key]);
        }
    
    $qs[$key]=$val;
    
    $hostpart=!empty($pts["host"])?$pts["scheme"]."://".$pts["host"]:"";
        
    return $hostpart.$pts["path"]."?".http_build_query($qs).(!empty($pts["fragment"])?"#".$pts["fragment"]:"");
    }



    public static function simpleTable(array $data) : string {
        if(empty($data)){return "";}

    $table="<table border=\"1\">";
    
    $table.="<tr>";
    foreach(array_keys((array)$data[0]) as $key){
    $table.="<th>$key</th>";  
    }
    
    $table.="</tr>";

    foreach($data as $row){
    $table.="<tr>";
        foreach($row as $value){
        $table.="<td> $value </td>";
        }
    $table.="</tr>
    ";
    }       

    $table.="</table>";
    return $table;
    }
    

}
/*
function strip_quotes($string=""){
    return str_replace("'", "", $string);
}
*/
