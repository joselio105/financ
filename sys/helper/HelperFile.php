<?php

final class HelperFile{
    private static $model;
    private static $proprietys;
    private static $name;
    private static $path;
    private static $error_msg;
    
    /**
     * Incorpora ao código HTML o ícone SVG escolhido
     * @param string $icon_name
     * @param string $path
     * @return string|NULL
     */
    public static function getSvgIcon($icon_name, $path=NULL){
        $path = (is_null($path) ? 'layout/img/icons/' : $path);
        $filename = "{$path}{$icon_name}.svg";
        if(file_exists($filename))
            return "\n\t".implode("\t", file($filename));
        else 
            return NULL;
    }
    
    /**
     * Cria um caminho caso ele ou parte dele não exista
     * @param string $path
     */
    public static function create_path($path){
        $brk = explode('/', $path);
        $new_dir = "";
        foreach ($brk as $dir){
            if($dir!='')
                $new_dir .= "{$dir}/";
                if(!file_exists($new_dir))
                    mkdir($new_dir);
        }
    }
    
    /**
     * Obtem o caminho para um arquivo
     * @param string $filename - caminho/arquivo.extensão
     * @return string
     */
    public static function getPath($filename){
        $path = explode('/', $filename);
        array_pop($path);
        return implode('/', $path).'/';
    }
    
    /**
     * Retorna a mensagem de erro correspondente ao erro obtido
     * Alias para getFileErros
     * @param integer $error
     * @return string
     */
    public static function getError($error){
        return self::getFileError($error);
    }
    
    /**
     * Transfere todos os aquivos em um dada pasta(origem) para outra (destino)
     * @param string $origem
     * @param string $destino
     */
    public static function move_pasta($origem, $destino){
        self::create_path($destino);
        $arquivos = glob($origem."*.*");
        if(file_exists($origem)){
            foreach ($arquivos as $f):
                $file_name = substr($f, strlen($origem));
                rename($f, $destino.$file_name);
            endforeach;
            rmdir($origem);
        }
    }
    
    /**
     * Copia um arquivo (ou o conteúdo de uma pasta) para um destino especificado
     * @param string $origem
     * @param string $destino
     */
    public static function copy($origem, $destino){
        $log = array();
        $fileName = '';
        $copy = array();
        
        if(is_file($origem)){
            $copy[0] = $origem;
            $fileName[0] = null;  
            $bkr = explode('/', $destino);
            $file = $bkr[count($bkr)-1];
            $path = substr($destino, 0, -(strlen($file)));
            self::create_path($path);
        }
        if(is_dir($origem)){
            $origem = (substr($origem, 0, -1)!='/'?$origem.'/':$origem);
            $destino = (substr($destino, 0, -1)!='/'?$destino.'/':$destino);
            self::create_path($destino);
            
            $copy = glob($origem."*.*");
            $log['origem'] = $origem;
            /*$log['destino']= $destino;
            $log['copy'] = $copy;*/
            foreach ($copy as $i=>$c)
                $fileName[$i] = substr($c, strlen($origem));
        }
        
        foreach ($copy as $i=>$c):
            $log[$i] = "Copiando {$c} para {$destino}{$fileName[$i]}";
            
            if(!copy($c, $destino.$fileName[$i]))
                HelperView::setAlert("ERRO AO COPIAR ARQUIVO: {$origem}");
            else 
                $log[$i].= " - OK";
        endforeach;
        
        if(strpos($origem, 'index.php'))
            return $log;
        else 
            return TRUE;
    }
    
    /**
     * Transfere o arquivo recebido (caminho completo) para a pasta tcc/_trash
     * @param string $file
     */
    public static function trash_file($file){
        $file = self::getFileName($file);
        $destino = str_replace('tcc/', 'tcc/_trash/', $file['path']);
        self::create_path($destino);
        
        rename("{$file['path']}/{$file['name']}", "{$destino}/{$file['name']}");
    }
    
    /**
     * Substitui o conteúdo de um arquivi
     * @param string $file
     * @param string $search - REGEX
     * @param string $replace
     * @return integer|boolean
     */
    public static function replaceInFile($file, $search, $replace){
        $arquivo = fopen($file, "r+");
        $count = array();
        
        if ($arquivo) {
            $novo_buffer = '';
            $line = 0;
            
            while (!feof($arquivo)) {
                $buffer = fgets($arquivo, filesize($file));
                $novo_buffer .= str_replace($search, $replace, $buffer, $count[$line]);
                $line++;
            }
            //var_dump($novo_buffer, $count);die;
            ftruncate($arquivo, 0);
            rewind($arquivo);
            fwrite($arquivo, $novo_buffer);
            fclose($arquivo);
            
            return array_sum($count);
        }else 
            return FALSE;
    }
    
    /**
     * Faz o download do arquivo recebido (caminho completo)
     * @param string $file
     * @param string $downAs
     */
    public static function downloadFile($file, $downAs=null){
        $file = self::getFileName($file);
        $downAs = (is_null($downAs)?"TCC-ARQ-USFC-DOWNLOAD_{$file['name']}":$downAs);
        
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="'.$downAs.'"');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file['fullPath']));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');
        
        readfile($file['fullPath']);
    }
    
    /**
     * Recupera o nome do arquivo em um caminho completo
     * @param string $file
     * @return array
     */
    public static function getFileName($file){
        $res = array();
        $brk = explode('/', $file);
        $res['fullPath'] = $file;
        $res['name'] = $brk[count($brk)-1];
        unset($brk[count($brk)-1]);
        $res['path'] = implode('/', $brk);
        
        $brk = explode('.', $res['name']);
        $res['extension'] = $brk[count($brk)-1];
        
        return $res;
    }
    
    /**
     * Retorna um array com informações sobre os arquivos de um caminho especificado e dos arquivos em suas subpastas
     * @param string $path - O Caminho a ser analizado
     * @return string[][]
     */
    public static function getFilesInfo($path){
        $dirIt = new RecursiveTreeIterator(new RecursiveDirectoryIterator($path));
        
        
        $k = 0;
        $files = array();
        foreach ($dirIt as $fileName=>$dirTree):
            if(is_file($fileName)){
                $files[$k] = self::getFileInfo($fileName);
                $k++;
        }
        endforeach;
        
        return $files;
    }
    
    /**
     * Retorna localização, nome, tamanho e data de modificação de um dado arquivo
     * @param string $fileName - Nome de um arquivo, incluindo sua localização
     * @return string[]
     */
    public static function getFileInfo($fileName){
        $fileName = str_replace('\\', '/', $fileName);
        $file = explode('/', $fileName);
        $length = -1 * strlen($file[count($file)-1]);
        
        $files = array(
            'nulo'=>$fileName,
            'path'=>substr($fileName, 0, $length),
            'name'=>$file[count($file)-1],
            'size'=>filesize($fileName),
            'chng'=>filemtime($fileName),
        );
        
        return $files;
    }
    
    /**
     * Retorna uma listagem das pastas de um dado caminho, exceto aquelas listadas no parâmetro $prohibed
     * @param string $path
     * @param string[]
     * @return string[]
     */
    public static function getDirs($path, array $prohibed=null){
        $dirIt = new RecursiveTreeIterator(new RecursiveDirectoryIterator($path));        
        
        $k = 0;
        $files = array();
        foreach ($dirIt as $fileName=>$dirTree):
        if(is_dir($fileName) AND !self::isDot($fileName)){
            $files[$k] = str_replace('\\','/', $fileName);
            $k++;
        }
        endforeach;        
        
        if(!is_null($prohibed)){
            $dirs = $files;
            $files = array();
            
            foreach ($prohibed as $p):
                $pattern = "/^({$p}{1})(.*)/";
                foreach ($dirs as $d):
                    if(preg_match($pattern, $d)!=1)
                        $files[] = $d;
                endforeach;
            endforeach;
        }
        
        return $files;
    }
    
    /**
     * Retorna a mensagem de erro correspondente ao erro obtido
     * @param integer $error
     * @return string
     */
    public static function getFileError($error){
        self::$error_msg = array(
            1=>'UPLOAD_ERR_INI_SIZE: O arquivo enviado excede o limite definido na diretiva upload_max_filesize do php.ini.',
            2=>'UPLOAD_ERR_FORM_SIZE: O arquivo excede o limite definido em MAX_FILE_SIZE no formulário HTML. ',
            3=>'UPLOAD_ERR_PARTIAL: O upload do arquivo foi feito parcialmente. ',
            4=>'UPLOAD_ERR_NO_FILE: Nenhum arquivo foi enviado.',
            6=>'UPLOAD_ERR_NO_TMP_DIR: Pasta temporária ausênte. ',
            7=>'UPLOAD_ERR_CANT_WRITE: Falha em escrever o arquivo em disco. ',
            8=>'UPLOAD_ERR_EXTENSION: Uma extensão do PHP interrompeu o upload do arquivo. O PHP não fornece uma maneira de determinar qual extensão causou a interrupção. Examinar a lista das extensões carregadas com o phpinfo() pode ajudar.',
        );
        return self::$error_msg[$error];
    }
    
    public static function jsonWrite($jsonFile, array $info){
        $infoOld = array();
        //Lê registros anteriores
        if(file_exists($jsonFile))
            $infoOld = self::jsonRead($jsonFile);
        else 
            self::create_path(self::getPath($jsonFile));
        
        //Junta os registros atuais com os anteriores
        $info = $infoOld + $info;
        
        //Transforma o array de registros em jSon
        $jsonData = json_encode($info, JSON_PRETTY_PRINT | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
        
        return file_put_contents($jsonFile, $jsonData);
        
        
    }
    
    /**
     * Lê os dados em um arquivo json
     * @param string $filename
     * @return array
     */
    public static function jsonRead($filename){
        $res = array();
        if(file_exists($filename)){
            $jsonFile = fopen($filename, 'r');
            $res = json_decode(fread($jsonFile, filesize($filename)), TRUE);                
            fclose($jsonFile);
        }
        
        return $res;
    }
    
    public static function readDir($dir){
        return glob($dir.'*.php');
    }
    
    public static function getClassName($filename){
        foreach (file($filename) as $line):
            if(strstr($line, 'class '))
                $brk = explode(' ', $line);
        endforeach;
        
        $terms = array('abstract', 'final', 'class');
        foreach ($terms as $term):
            if(is_numeric(array_search($term, $brk)))
                unset($brk[array_search($term, $brk)]);
        endforeach;
        
        $res = trim(array_shift($brk));
            
        return (substr($res, -1)=='{' ? substr($res, 0, -1) : $res);
    }
    
    public static function getMethods($class) {
        $classes = array('__construct', '__toString');
        $res = array();
        
        $maker = 'mkr_';
        if(substr($class, 0, strlen($maker))==$maker)
            $filename = PATH_MAKER."controller/{$class}_controller.php";
        else
            $filename = PATH_CONTROLLER."{$class}_controller.php";
        
        if(file_exists($filename)){
            include_once $filename;
            if(class_exists($class)){
                $res = get_class_methods($class);
                
                foreach ($classes as $noClass):
                    if(is_numeric(array_search($noClass, $res)))
                        unset($res[array_search($noClass, $res)]);
                endforeach;
            }
        }
        return $res;
    }
    
    public static function listAllControllers(){
        $res = array();
        
        $res = self::listClasses(PATH_CONTROLLER, NULL);
        /*$res['maker'] = self::listClasses(PATH_MAKER.'controller/', NULL);
        $res = array_merge($res['custom'], $res['maker']);*/
        
        return $res;
    }
    
    public static function listAllActions(){
        $res = array();
        
        foreach (self::listAllControllers() as $controller):
            foreach (self::getMethods($controller) as $action)
                $res["{$controller}/{$action}"] = "{$controller}/{$action}";
        endforeach;
        
        return $res;
    }
    
    public static function listClassesOnDir($dir){
        $class = array();
        
        foreach (self::readDir($dir) as $i=>$model):
            $class[$i]['file'] = $model;
            $class[$i]['name'] = HelperFile::getClassName($model);
        endforeach;
        
        return $class;
    }
    
    public static function listClasses($dir, $firstElement='Nenhum'){
        $res = array();
        if(!is_null($firstElement))
            $res[''] = $firstElement;
        
        foreach (self::readDir($dir) as $class)
            $res[self::getClassName($class)] = self::getClassName($class);
            
            return $res;
    }
    
    /**
     * Verifica se a pasta enviada é . ou ..
     * @param string $dir
     * @return boolean
     */
    private static function isDot($dir){
        $pattern = '/(.*)([\.]{1,2})$/';
        if(preg_match($pattern, $dir)!=0)
            return TRUE;
        else 
            return FALSE;
        
    }
}
