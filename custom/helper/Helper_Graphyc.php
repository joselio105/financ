<?php

final class Helper_Graphyc{
    
    private $image;
    private $fileName;
    private $scale;
    private $maxValue;
    private $width;
    private $height;
    
    public function __construct($value, $limit, $categoria_id, $maxValue=1000){
        $this->width = 200;
        $this->height = 300;
        $this->maxValue = $maxValue;
        $this->scale = $this->height / $this->maxValue;
        
        $path = PATH_CONTENT.'graphyc/';
        HelperFile::create_path($path);
        $this->fileName = "{$path}{$categoria_id}.jpg";
        
        $this->image = imagecreate($this->width, $this->height);
        
        $color_back = imagecolorallocate($this->image, 255, 255, 255);
        $color_black = imagecolorallocate($this->image, 0, 0, 0);
        $color_red = imagecolorallocate($this->image, 255, 0, 0);
        $color_green = imagecolorallocate($this->image, 0, 255, 0);
        
        $fontSize = 12;
        $fontFile = PATH_FONTS.'Arial.ttf';
        
        $this->grid();
        $this->rectangle($limit, $color_green);
        //imagettftext($this->image, $fontSize, 0, 0, $this->height-$limit * $this->scale, $color_green, $fontFile, HelperData::printLikeMoney($limit));
        $this->rectangle($value, $color_red);
		$percent = ($limit >0 ? $value/$limit : 0);
        imagettftext($this->image, $fontSize, 0, $this->width / 7, 3 * $this->height / 4, $color_black, $fontFile, HelperData::printLikePercentage($percent));
        
        
        imagejpeg($this->image, $this->fileName);
        imagedestroy($this->image);
    }
    
    public function __toString(){
        return "<img src=\"".URI."{$this->fileName}\">";
    }
    
    private function grid(){
        $interval = 25;
        $color_grid = imagecolorallocate($this->image, 204, 204, 204);
        
        for($y=0; $y<=$this->height; $y=$y + $interval * $this->scale)
            imageline($this->image, 0, $y, $this->width, $y, $color_grid);
    }
    
    /**
     * Cria um retangulo na imagem
     * @param double $value
     * @param resource $color
     */
    private function rectangle($value, $color){
        imagefilledrectangle($this->image, $this->width/2, $this->height, $this->width, $this->height-$value * $this->scale, $color);
    }
}

