<?php
namespace defrindr\xmath\traits;

/**
 * SPLBASED
 * 
 * Defri Indra M
 * 10 April 2020
 * 
 */
trait SPLBASED {
    

    /**
     * getCount
     * get length of array $pers1 & $pers2
     * 
     * @return int $count
     * 
     */
    public function getCount($pers1,$pers2){
        if(count($pers1["variable"]) == count($pers2["variable"])){
            $count = count($pers1["variable"]);
        }else{
             throw new \exception("Jumlah dari variable tidak sama" . PHP_EOL);
        }
        return $count;
    }


    /**
     * getCharOnly
     * 
     * @param string $val
     * 
     * @return string $ret
     */
    public function getCharOnly($val){
        $ret = preg_replace("/.?\d/",'',$val);
        return $ret;
    }


    /**
     * getNumOnly
     * 
     * @param string $val
     * 
     * @return string $ret
     */
    public function getNumOnly($val){
        $ret = preg_replace("/[a-zA-Z]/",'',$val);
        return $ret;
    }



    /**
     * setValueToMutipler
     * 
     * @return array $ret ["var" => "a","val1" => 1,"val2" => 2]
     * 
     */
    public function setValueToMutipler($pers1, $pers2,$var = ""){
        $charVarIS = $var;
        $valForVar1 = 0;
        $valForVar2 = 0;


        $count = $this->countVariable;

        if($charVarIS == ""){
            /**
             * extract character variable
             */
            for($i=0; $i< $count; $i++){
                for($j=0; $j < count($pers2["variable"]); $j++){
                    if(
                        $this->getCharOnly($pers1["variable"][$i]) == $this->getCharOnly($pers2["variable"][$j])
                    ){
                        $charVarIS = $this->getCharOnly($pers1["variable"][$i]);
                        break;
                    }
                }
            }
        }

        /**
         * get value of $charVarIS in $pers1
         */
        for($i=0; $i< $count; $i++){
            if($this->getCharOnly($pers1["variable"][$i]) == $charVarIS){
                $valForVar1 = $this->getNumOnly($pers1["variable"][$i]);
                if($valForVar1 < 0){
                    $valForVar1 *= -1;
                }
                break;
            }
        }

        /**
         * get value of $charVarIS in $pers2
         */
        for($i=0; $i< $count; $i++){
            if($this->getCharOnly($pers2["variable"][$i]) == $charVarIS){
                $valForVar2 = $this->getNumOnly($pers2["variable"][$i]);
                if($valForVar2 < 0){
                    $valForVar2 *= -1;
                }
                break;
            }
        }

        $valForVar1 = preg_replace("/^\+/","",$valForVar1);
        $valForVar2 = preg_replace("/^\+/","",$valForVar2);

        $arr = [
            "var" => $charVarIS,
            "val1" => $valForVar1,
            "val2" => $valForVar2,
        ];

        return $arr;
    } 


    /**
     * multipleNumber
     * make $pers1 and $pers2 balance
     * 
     * @param array $arr -> ["var" => "a","val1" => 0, "val2" => 0]
     * @param array $pers1 -> ["result" => 0,"variable" => [] ]
     * @param array $pers2 -> ["result" => 0,"variable" => [] ]
     * 
     * 
     * @return array  ["pers1" => $pers1,"pers2" => $pers2 ]
     * 
     */
    public function multipleNumber($arr,$pers1,$pers2){
        $charVarIS = $arr["var"];
        $valForVar1 = $arr["val1"];
        $valForVar2 = $arr["val2"];

        $count = $this->countVariable;
        

        for($i=0; $i< $count; $i++){
            // persamaan 1
            $whatIsMyVar1 = $this->getCharOnly($pers1["variable"][$i]);
            $whatIsMyVal1 = $this->getNumOnly($pers1["variable"][$i]);
            $pers1["variable"][$i] = ((int)$whatIsMyVal1 * (int)$valForVar2) . $whatIsMyVar1;
            // persamaan 2
            $whatIsMyVar2 = $this->getCharOnly($pers2["variable"][$i]);
            $whatIsMyVal2 = $this->getNumOnly($pers2["variable"][$i]);
            $pers2["variable"][$i] = ((int)$whatIsMyVal2 * (int)$valForVar1) . $whatIsMyVar2;
        }

        $pers1["result"] *= (int)$valForVar2;
        $pers2["result"] *= (int)$valForVar1;

        return [
            "pers1" => $pers1,
            "pers2" => $pers2
        ];

    }




    /**
     * addingOne
     * adding number 1 if variable only
     * 
     * @param string $pers
     * 
     * @return string $pers
     */
    public function addingOne($pers){
        $pers = preg_replace('/([-|+])([A-Za-z])/', '$1 1$2', $pers);
        $pers = preg_replace('/^([A-Za-z])/', '1 $1', $pers);
        $pers = preg_replace('/\s/', '', $pers);

        return $pers;
    }


    /**
     * validationForVar
     * 
     * check var in $pers1 & $pers2 , match or not
     */
    public function validationForVar(){
        $count = $this->countVariable;
        $varMatch = 0;

        foreach($pers1["variable"] as $r1){
            foreach($pers2["variable"] as $r2){
                if($this->getCharOnly($r1) == $this->getCharOnly($r2)){
                    $varMatch++;
                }
            }
        }

        if($varMatch != $count){
            throw new \exception("Variable di persamaan 1 dan 2 harus sama." . PHP_EOL);
        }

    }

    /**
     * parser
     * 
     * @param string $source
     * 
     * @return array $res -> [ "result" =>1, "variable" => []]
     */
    public function parser($source){
        $result = 0;
        $var = [];


        $source = explode("=",$source);
        $result = $source[1];

        preg_match_all("/[^\=]?\d+[A-Za-z]?\^?\d?/",$source[0],$res);
        $res = $res[0];
        if($res[1] == null){
            $res[1] = 1;
        }
        for($i=0;$i<count($res);$i++){
            if(preg_replace("/.?\d/",'',$res[$i]) == ""){
                $result += ((int)$res[$i] * -1);
                unset($res[$i]);
            }
        }

        
        $res = [
            "result" => $result,
            "variable" => $res,
        ];

        return $res;

    }


    /**
     * elimination
     *
     * @param string $var
     * @param array $pers1 -> ["result" => 0, "variable" => []]
     * @param array $pers2 -> ["result" => 0, "variable" => []]
     * 
     */
    public function elimination($var, $pers1, $pers2){

        $charVarIS = $var["var"];
        $valForVar1 = 0;
        $valForVar2 = 0;

        $new_variable = [
            "result" => 0,
            "variable" => []
        ]; 

        
        
        $count = $this->countVariable;

        $valForVar1 = $this->getNumOfVar($charVarIS,$pers1,$count);
        $valForVar2 = $this->getNumOfVar($charVarIS,$pers2,$count);



        if( $valForVar1 > 0 && $valForVar2 > 0 ){
            $operator = "-";
        }else{
            $operator = "+";
        }




        for($i=0; $i< $count; $i++){
            for($j=0; $j < $count; $j++){
                if(
                    $this->getCharOnly($pers1["variable"][$i]) == $this->getCharOnly($pers2["variable"][$j])
                ){
                    $whatIsMyVar1 = $this->getCharOnly($pers1["variable"][$i]);
                    $whatIsMyVal1 = $this->getNumOnly($pers1["variable"][$i]);

                    $whatIsMyVar2 = $this->getCharOnly($pers2["variable"][$j]);
                    $whatIsMyVal2 = $this->getNumOnly($pers2["variable"][$j]);

                    if($operator == "+"){
                        $result = ($whatIsMyVal1 + $whatIsMyVal2) . $whatIsMyVar1;
                    }else{
                        $result = ($whatIsMyVal1 - $whatIsMyVal2) . $whatIsMyVar2;
                    }

                    if($this->getNumOnly($result) != 0){
                        array_push($new_variable["variable"],
                            $result);
                    }

                }
            }
        }

        if($operator == "+"){
            $new_variable["result"] = $pers1["result"] + $pers2["result"];
        }else {
            $new_variable["result"] = $pers1["result"] - $pers2["result"];
        }

        if( count($new_variable['variable']) <= 0 ){
            throw new \Exception("Terjadi error karena konst dari semua variable yang sama." . PHP_EOL);
            
        }

        return $new_variable;
    }

    /**
     * getNumOfVar
     * 
     * @param string $var
     * @param array $pers -> ["result" => 0, "variable" => []]
     * @param int $count
     * 
     * @return int
     */
    public function getNumOfVar($var,$pers,$count){
        for($i=0; $i<$count; $i++){
            if($this->getCharOnly($pers["variable"][$i]) == $var){
                return $this->getNumOnly($pers["variable"][$i]);
            }
        }
    }

    
    /**
     * toString
     * make array to string
     * 
     * @param array $arr -> ["result" => 0, "variable" => []]
     * 
     * @return string $template
     */
    public function toString($arr) : string {
        $template = "";
        if(isset($arr["result"])){
            $template = " = ". $arr["result"];
        }

        if(isset($arr["variable"])){
            $arr["variable"] = array_reverse($arr["variable"]);
            foreach($arr["variable"] as $r) {
                if( $this->getNumOnly($r) > 0){
                    $template = "+".$r . $template;
                }else{
                    $template = $r . $template;
                }
            }
        }

        return preg_replace("/^\+/","",$template);

    }



    /**
     * findLastVar
     * @param array $arr -> ["x" =>1, "y" => 2]
     * @param array $pers -> [ "return" => 0, "variable" => [] ]
     * @return array        [ "return" => 0, "variable" => [] ]
     */
    public function findLastVar($arr,$pers){
        $result = $pers["result"];
        $variable = [];

        $count = count($pers["variable"]);

        for($i = 0; $i < $count; $i++) {
            foreach($arr as $key => $val) {
                if(
                    $this->getCharOnly($pers["variable"][$i]) == $key
                ){

                    $konst = $this->getNumOnly($pers["variable"][$i]);
                    $result += ($konst * $val) * -1;
                    unset($pers["variable"][$i]);
                    break;
                }
            }
        }

        foreach($pers["variable"] as $val){
            array_push($variable,$val);
        }

        return [
            "result" => $result,
            "variable" => $variable
        ];


    }


}
