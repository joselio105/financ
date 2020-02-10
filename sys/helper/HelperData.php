<?php

final class HelperData{
    
    /**
     * Exibe um valor em bytes
     * @param float $filesize
     * @return string
     */
    public static function getFileSize($filesize){
        $unit = ' Bytes';
        if($filesize > FILE_SIZE_KILO AND $filesize <= FILE_SIZE_MEGA){
            $filesize = $filesize / FILE_SIZE_KILO;
            $unit = ' Kb';
        }elseif ($filesize > FILE_SIZE_MEGA AND $filesize <= FILE_SIZE_GIGA){
            $filesize = $filesize / FILE_SIZE_MEGA;
            $unit = ' Mb';
        }elseif ($filesize > FILE_SIZE_GIGA){
            $filesize = $filesize / FILE_SIZE_GIGA;
            $unit = ' Gb';
        }
        
        return number_format($filesize, 2, ',', '.').$unit;
    }
    
    /**
     * Exibe um valor como moeda corrente (R$)
     * @param float $valor
     * @return string
     */
    public static function printLikeMoney($valor){
        return 'R$ '.number_format($valor, 2, ',', '.');
    }
    
    /**
     * Exibe um valor como porcentagem (%)
     * @param float $valor
     * @return string
     */
    public static function printLikePercentage($valor){
        return number_format($valor*100, 2, ',', '.').'%';
    }
    
    /**
     * Retorna o mês na forma textual
     * @param int $mes
     * @return string
     */
    public static function mesText($mes){
        $meses = array(NULL, 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro');
        
        return $meses[$mes];
    }
    
}