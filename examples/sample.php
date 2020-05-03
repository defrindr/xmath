<?php

require_once __DIR__ .'/../vendor/autoload.php';

use xmath\xmath\spl\SPLDV;
use xmath\xmath\spl\SPLTV;

$spldv = new SPLDV("2x+y-z=3","-x-y+z=-1");
echo $spldv;

$spltv = new SPLTV("2x+y-z=3","-x-y+z=-1","-x+y-z=2");
echo $spltv;



/**
 * return array
 */
print_r((new SPLDV("2x+y=5","x+y=10"))->getArray());


/**
 * Output :
 * Array
 * (
 *     [x] => -5
 *     [y] => 15
 * )
 */







/**
 * get value from specific variable
 */


$spldv = new SPLDV("2a+y=5","a+y=10");
$spldv2 = new SPLDV("2c+d=5","c+d=10");
$a = $spldv->a;
$b = $spldv->y;
$c = $spldv2->c;
$d = $spldv2->d;
echo $c;

/**
 * Output :
 * 5
 */


/**
 * get step to solved
 */
$spldv = new SPLDV("2a+y-5=0","a+y-10=0");
echo $spldv->getStep();


/**
 * Output :
 * 2a+1y-5=0  		x 1
 * 1a+1y-10=0  		x 1
 * ---------------------------
 * 2a+1y = 5
 * 1a+1y = 10
 * ---------------------------
 * 1a = -5
 * a = -5/1
 * a = -5
 * ---------------------------
 * 2a+1y = 5
 * 2(-5)+1y = 5
 * 1y = 15
 * y = 15/1
 * y = 15
 */



/**
 * Handle error
 */
try{
	echo (new SPLDV("x+y=5","x+y=10"));
}catch(Exception $e){
	echo $e->getMessage();
}

/**
 * Output:
 * Terjadi error karena konst dari semua variable yang sama.
 */


