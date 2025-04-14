<?php
namespace App\Libraries;

use \DateTime;

use function Ramsey\Uuid\v1;

/**
 * HTML paginacion class.
 *
*/
class WhereBuilder {
    
    public $list=[];
    
    public $receivedList=[];
    protected $acceptEmptyList=[];
    
    public function Received(array $arr){
    $this->receivedList = $arr;
    }
    
    public function AcceptEmpty(string $key){
        $this->acceptEmptyList[]=$key;
    }
    
    /**
     * @param string $key
     * @param string $str
     * @param bool $acceptEmpty
     * @tutorial sprintf format
     * @tutorial LIKE = LIKE'~' 
     */
    public function Customize(string $key, string $str, bool $acceptEmpty = false){
        if(!isset($this->receivedList[$key])){return;}

        if($acceptEmpty == false && 
        ($this->receivedList[$key] === null || $this->receivedList[$key] === false || $this->receivedList[$key] === "") ){
            return;
        }

        if(is_array($this->receivedList[$key])){
            $this->list[$key] = vsprintf($str, $this->receivedList[$key]);
        }
        else{ 
            $this->list[$key] = sprintf($str, (string)$this->receivedList[$key]);
        }        
    }
    
    public function CustomValue(string $key, string $str){
        if(isset($this->receivedList[$key])){
            $this->list[$key]= $str;
        }else{
            $this->Add($key,$str);
        }        
    }

    public function Add(string $str) : string {
        $newKey = "_ck".rand(0,999);
        $this->list[$newKey]= $str;
        return $newKey;
    }

    public function Not(string $key){
      if(!isset($this->receivedList[$key])){return;}

        if(is_array($this->receivedList[$key])){
       $arr=[];
            foreach($this->receivedList[$key] as $vv){$arr[]="'".addslashes($vv)."'";}
        $this->list[$key] = "$key NOT IN (".implode(",",$arr).")";
        }else{
            $this->list[$key] = "$key != '".strval($this->receivedList[$key])."'";
        }
    }

    
    public function Remove(string $key){
        if(isset($this->receivedList[$key])){unset($this->receivedList[$key]);}
    }


    
    public function String(){
        //Borra vacios
        foreach($this->receivedList as $k=> $v){
            if((is_null($v) || $v === "" || $v === false) && !in_array($k,$this->acceptEmptyList) || $v===[]){
                unset($this->receivedList[$k]); 
            }
        }

        foreach($this->receivedList as $k => $v){
            if(!isset($this->list[$k])){
                if(is_string($v)){
                    $this->list[$k]="$k = '".$v."'";
                }
                elseif(is_array($v)){
                    $arr=[];
                        foreach($v as $vv){$arr[]="'".addslashes($vv)."'";}
                    $this->list[$k] = "$k IN (".implode(",",$arr).")";
                }
            }            
        }


        if(empty($this->list)){return "";}

    $str = "WHERE ";
    $str.= implode(" AND ",$this->list);
    return $str;
    }
}
