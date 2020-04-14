<?php

class TomlService{

    public static function ParseFile($file){
        $status = null;
        $toml = array();
        try {
            $toml = Yosymfony\Toml\Toml::Parse($file);
        } catch (\Exception $e) {
            $status = $e->getMessage();
        }
        return array($toml,$status);
    }

    public static function ParseContent($content){
        return self::ParseFile($content);
    }

}

