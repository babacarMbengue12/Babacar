<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 24/03/2019
 * Time: 16:12
 */

namespace Babacar\Parsers;


class FileParser
{

    const PRAM_SEPARATE = ":";


    public static function env_parser($file = '.env'){
        if(!file_exists($file)){
            return trigger_error('le fichier .env est necessaire pour contunue',E_USER_NOTICE);
        }
        $contents = file_get_contents($file);
        $rows = array_map('trim',array_filter(explode(PHP_EOL,$contents)));
        $prams = [];
        foreach ($rows as $row){
            if(strpos($row,self::PRAM_SEPARATE) !== false){
                $row = array_map('trim',explode(self::PRAM_SEPARATE,$row));
                $prams[$row[0]] =$row[1];
            }
        }
        return $prams;
    }


}