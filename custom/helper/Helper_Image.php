<?php
/**
 * @version 29/11/2018 10:43:00
 * @author jose.helio@gmail.com
 *
 */
final class Helper_Image_Versions{
    
    private $image;
    private $fileName;
    private $type;
    private $width;
    private $height;
    
    public function __construct($filename){
        $this->type = mime_content_type($filename);
        $this->fileName = basename($filename);
        switch ($this->type):
            case FILE_TYPE_JPG:
                $this->image =  imagecreatefromjpeg($filename);
                break;
            case FILE_TYPE_PNG:
                $this->image = imagecreatefrompng($filename);
                break;
            default:
                HelperView::setAlert("O arquivo {$filename} não é um tipo suportado!");
                return;
        endswitch;
        
        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);
        
        $this->resize('medium');
        $this->resize('mini');
    }
    
    public function __destruct(){
        imagedestroy($this->image);
    }
    
    private function resize($size){
        $sizes = array(
            'medium'=>array(
                'width'=>400,
                'height'=>300
            ),
            'mini'=>array(
                'width'=>200,
                'height'=>150
            ),
        );
        $path = PATH_CONTENT."{$size}/";
        HelperFile::create_path($path);
        
        //Calcula ponto de insersão na nova imagem
        if(($this->width / $this->height) <= 1){
            $expectedWidth = $sizes[$size]['width'];
            $expectedHeight = $this->height * $sizes[$size]['width'] / $this->width;
        }else{
            $expectedWidth = $this->width * $sizes[$size]['height'] / $this->height;
            $expectedHeight = $sizes[$size]['height'];
        }
        $insPointX = ($sizes[$size]['width'] - $expectedWidth) / 2;
        $insPointY = ($sizes[$size]['height'] - $expectedHeight) / 2;
        
        //Cria uma imagem resource
        $newImage = imagecreatetruecolor($sizes[$size]['width'], $sizes[$size]['height']);
        
        //Define cores
        $colorBlack = imagecolorallocatealpha($newImage, 0, 0, 0, 150);
        $colorRed = imagecolorallocatealpha($newImage, 255, 0, 0, 0);
        
        //Copia a imagem original redimensionando-a
        imagecopyresampled($newImage, $this->image, $insPointX, $insPointY, 0, 0, $expectedWidth, $expectedHeight, $this->width, $this->height);
        
        //Cria um retangulo no quarto inferior da imagem
        imagefilledrectangle($newImage, 0, $sizes[$size]['height']*(3/4), $sizes[$size]['width'], $sizes[$size]['height'], $colorRed);
        
        //Escreve um texto sobre a imagem
        imagettftext($newImage, $sizes[$size]['height']/20, 0, $sizes[$size]['width']*(2/8), $sizes[$size]['height']*(7/8), $colorBlack, PATH_FONTS.'PermanentMarker.ttf', "Aline Verissimo Ilustrações");
        
        //Gera o arquivo da imagem
        imagejpeg($newImage, $path.$this->fileName);
        
        //libera a memoria alocada
        imagedestroy($newImage);
    }
    
}