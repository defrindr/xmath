<?php
namespace defrindr\xmath\spl;
use defrindr\xmath\traits\SPLBASED;

// include_once('../traits/SPLBASED.php');

/**
 * Defri Indra M
 * 7 April 2020
 * Mencari sistem persamaan linear 2 variable
 * 
 */
class SPLDV {
    use SPLBASED;

    private $countVariable = 0;

    private $withStep = "";
    
    private $XY = [];

    /**
     * __construct
     * 
     * @param string @pers1
     * @param string @pers2
     * 
     */
    public function __construct($pers1,$pers2){
        
        
        // add "1" if only declare variable
        $pers1 = $this->addingOne($pers1);
        $pers2 = $this->addingOne($pers2);

        
        // change from string to array
        $this->pers1 = $this->parser($pers1);
        $this->pers2 = $this->parser($pers2);

        $this->countVariable = $this->getCount($this->pers1,$this->pers2);
        

        $this->validationForVar();

        // get requirement to multipleNumber
        $multi = $this->setValueToMutipler($this->pers1, $this->pers2);

        
        
        $this->addStep($pers1 . "  \t\tx ". $multi["val2"] ."\n")
            ->addStep($pers2 . "  \t\tx ". $multi["val1"]) ;
        
        $res = $this->multipleNumber($multi,$this->pers1,$this->pers2);
        
        $this->pers1 = $res["pers1"];
        $this->pers2 = $res["pers2"];


        
        
        $this->addStep("\n---------------------------\n")
            ->addStep($this->toString($this->pers1) . "\n")
            ->addStep($this->toString($this->pers2) . "\n");

        
        // elimination 1 variable
        $result = $this->elimination($multi,$this->pers1, $this->pers2);


        $whatIsMyVar1 = $this->getCharOnly($result["variable"][0]);
        $whatIsMyVal1 = $this->getNumOnly($result["variable"][0]);
        $x = (int)$result["result"] / (int)$whatIsMyVal1;

        // withStep -> find val x
        $this->addStep("\n---------------------------\n")
            ->addStep($this->toString($result) . "\n")
            ->addStep($whatIsMyVar1 . " = " . $result["result"] . "/" . $whatIsMyVal1 . "\n")
            ->addStep($whatIsMyVar1 . " = " . $x . "\n");



        $result = $this->findLastVar([$whatIsMyVar1 => $x],$this->pers1);

        
        $whatIsMyVar2 = $this->getCharOnly($result["variable"][0]);
        $whatIsMyVal2 = $this->getNumOnly($result["variable"][0]);
        $y = (int)$result["result"] / (int)$whatIsMyVal2;


        // withStep -> find val y
        $this->addStep("\n---------------------------\n")
            ->addStep($this->replaceVarX($whatIsMyVar1,$x,$this->pers1) . "\n")
            ->addStep($this->toString($result) . "\n")
            ->addStep($whatIsMyVar2 . " = " . $result["result"] . "/" . $whatIsMyVal2 . "\n")
            ->addStep($whatIsMyVar2 . " = " . $y . "\n");


        $this->XY = 
                [
                    $whatIsMyVar1 => $x,
                    $whatIsMyVar2 => $y
                ];        

        return $this;
    }

    public function addStep($str) : self {
        $this->withStep .= $str;
        return $this;
    }


    /**
     * validationForVar
     * 
     * check var in $pers1 & $pers2 , match or not
     */
    public function validationForVar() {
        $count = $this->countVariable;
        $varMatch = 0;

        foreach($this->pers1["variable"] as $r1){
            foreach($this->pers2["variable"] as $r2){
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
     * replaceVarX
     * 
     * @param string $var
     * @param int $val
     * @param array $pers1 -> [ "result" => 0,"variable" => []]
     * 
     * @return string
     */
    public function replaceVarX($var,$val,$pers1) : string {
        return $this->toString($pers1) .PHP_EOL. preg_replace("/".$var."/","(" . $val .")",$this->toString($pers1));
    }



    /**
     * getStep
     * return step to solved spldv
     * 
     * @return string
     */
    public function getStep() : string{
        return $this->withStep;
    }

    /**
     * magic function
     * 
     * @return string $ret
     */
    public function __toString() : string{
        $ret = "";
        foreach($this->XY as $key => $val){
            $ret .= $key . " => ".$val . PHP_EOL ;
        }

        return $ret;
    }

    public function __get($field){
        foreach ($this->XY as $key => $value) {
            if($field == $key){
                return $this->XY[$key] . PHP_EOL;
            }
        }
    }

    /**
     * getArray
     * 
     * @return array $this->XY
     */
    public function getArray() : array {
        return $this->XY;
    }


}
