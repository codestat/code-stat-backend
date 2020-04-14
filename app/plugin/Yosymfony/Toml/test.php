<?php
$parth = dirname(__FILE__)."/";
$include_list = array(
    "Lexer.php",
    "Parser.php",
    "Token.php",
    "Toml.php",
    "TomlBuilder.php",
    "Exception/ExceptionInterface.php",
    "Exception/RuntimeException.php",
    "Exception/DumpException.php",
    "Exception/LexerException.php",
    "Exception/ParseException.php",
);
foreach($include_list as $file){
    include("{$parth}{$file}");
}
$a = <<<EOD
name="word"
desc="单词"
[field]
pkid="uint,11,主键ID"
name="string,64,单词名称"
alias_name="string,255,单词别名"
cdesc="string,2048,中文注释"
status="int,2,状态"
level="uint,4,星级"
rate="uint,4,频率"
score="uint,11,分数"
image="string,255,图片"
phonetic_uk="string,255,音标英"
phonetic_us="string,255,音标美"


[field.desc]
status="aa"

[advance]
[advance.status]
0="隐藏"
1="显示"

EOD;
$array = Yosymfony\Toml\Toml::Parse($a);


print_r($array);
