<?php
spl_autoload_register(
    function ($class)
    {
        $class = str_replace("\\","/",$class);
        $class = trim($class, '/');
        $path1  = __DIR__.'/../'.$class.'.php';
        $path2  = __DIR__.'/../lib/'.$class.'.php';
        if(file_exists($path1)){
            include_once $path1;
        }
        elseif(file_exists($path2)){
            include_once $path2;
        }
    }
);
?>
