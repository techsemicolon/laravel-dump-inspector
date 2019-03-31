<?php 

use Techsemicolon\InspectorDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

if(!function_exists('idump')){

    function idump()
    {
        $arguments = func_get_args();

        if(empty($arguments)){
            
            array_map(function ($x) {
                (new InspectorDumper)->dump((new VarCloner)->cloneVar($x));
            }, $arguments);
            
            return;
        }

        if(count($arguments) == 1)
        {
            array_map(function ($x) {
                (new InspectorDumper)->dump((new VarCloner)->cloneVar($x));
            }, func_get_args());

            return $arguments[0];
        }

        array_map(function ($x) {
            (new InspectorDumper)->dump((new VarCloner)->cloneVar($x));
        }, $arguments);
    }
}