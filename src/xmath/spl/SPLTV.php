<?php
namespace defrindr\xmath\spl;
use defrindr\xmath\traits\SPLBASED;



/**
 * Defri Indra M
 * 7 April 2020
 * Mencari sistem persamaan linear 2 variable
 * 
 */
class SPLTV {

    use SPLBASED;

    private $countVariable = 0;

    private $withStep = "";
    
    private $multiVar = "";

    private $isWithStep = false;
    
    private $XY = "";

    private $XYZ = [];

    /**
     * __construct
     * 
     * @param string @pers1
     * @param string @pers2
     * 
     */
    public function __construct($pers1,$pers2,$pers3){
        
        
        // add "1" if only declare variable
        $pers1 = $this->addingOne($pers1);
        $pers2 = $this->addingOne($pers2);
        $pers3 = $this->addingOne($pers3);

        
        // change from string to array
        $this->pers1 = $this->parser($pers1);
        $this->pers2 = $this->parser($pers2);
        $this->pers3 = $this->parser($pers3);

        
        
        $this->countVariable = $this->getCount($this->pers1,$this->pers2);
        
        
        $newArray1 = $this->merge2pers($this->pers1,$this->pers2);
        $newArray2 = $this->merge2pers($this->pers1,$this->pers3);
        
        
        $this->countVariable = $this->getCount($newArray1,$newArray2);
        

        $resultXY = $this->findXY(
            $newArray1,
            $newArray2
        );

        $varZ = $this->findLastVar($resultXY,$this->pers3);

        $this->extractNum($varZ,$z,$varZ);

        $this->XYZ += $resultXY;
        $this->XYZ +=[$varZ => $z];
        
        return $this;
    }

    public function extractNum($pers,&$ret,&$var){
        $var = $this->getCharOnly($pers["variable"][0]);
        $whatIsMyVal1 = $this->getNumOnly($pers["variable"][0]);
        $ret = (int)$pers["result"] / (int)$whatIsMyVal1;

    }

    public function findXY($pers1, $pers2){
         
        // get requirement to multipleNumber
        $multi = $this->setValueToMutipler($pers1, $pers2);
        
        $res = $this->multipleNumber($multi,$pers1,$pers2);
        
        $pers1 = $res["pers1"];
        $pers2 = $res["pers2"];


        
        // elimination 1 variable
        $result = $this->elimination($multi,$pers1, $pers2);

        
        $whatIsMyVar1 = $this->getCharOnly($result["variable"][0]);
        $whatIsMyVal1 = $this->getNumOnly($result["variable"][0]);
        $x = (int)$result["result"] / (int)$whatIsMyVal1;


        $result = $this->findLastVar([$whatIsMyVar1 => $x],$pers1);

        
        $whatIsMyVar2 = $this->getCharOnly($result["variable"][0]);
        $whatIsMyVal2 = $this->getNumOnly($result["variable"][0]);
        $y = (int)$result["result"] / (int)$whatIsMyVal2;


        return [
                    $whatIsMyVar1 => $x,
                    $whatIsMyVar2 => $y
                ];
    }


    /**
     * merge2pers
     * @param  array $pers1 -> [ "return" => 0, "variable" => [] ]
     * @param  array $pers2 -> [ "return" => 0, "variable" => [] ]
     * @return array           [ "return" => 0, "variable" => [] ]
     */
    public function merge2pers($pers1,$pers2){

        // get requirement to multipleNumber
        $multi = $this->setValueToMutipler($pers1,$pers2,$this->multiVar);

        if($this->multiVar == ""){
            $this->multiVar = $multi["var"];
        }


        $double = $this->multipleNumber($multi,$pers1,$pers2);


        $newpers = $this->elimination($multi, $double["pers1"], $double["pers2"]);
        return $newpers;
    }

    public function __toString(){
        $ret = '';
        foreach($this->XYZ as $key => $val){
            $ret .= $key . " = " . $val . "\n";
        }
        return $ret;
    }



}