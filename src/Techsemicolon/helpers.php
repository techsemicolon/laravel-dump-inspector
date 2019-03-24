<?php 

use Techsemicolon\InspectorDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

if(!function_exists('idd')){

    function idd()
    {
        array_map(function ($x) {
            (new InspectorDumper)->dump((new VarCloner)->cloneVar($x));
        }, func_get_args());
    }
}