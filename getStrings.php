<?php
function get_comments($xmlstr){
    $doc = new DOMDocument;

    $doc->preserveWhiteSpace = false;
    $doc->loadXML($xmlstr);

    $xpath = new DOMXPath($doc);

    $comments = [];

    foreach ($xpath->query('//comment()') as $comment)
    {
        $comments[trim($comment->nextSibling->attributes->item(0)->value)] = $comment->textContent;
    }
    return $comments;
}

function getNeatFileName($filename){
    $filename = str_replace("strings_", "", $filename);
    $filename = str_replace("_", " ", $filename);
    $filename = str_replace(".xml", "", $filename);
    return ucfirst($filename);
}

function find_by_name($resources, $name){
    foreach($resources as $resNode) {
        if ($resNode['name'] == $name)
            return $resNode;
    }
}
function get_file_as_id($file){
    return str_replace(".xml", "", $file);
}

$resources_dir = 'xml/';
$default_lang = $resources_dir.'values/';
$default_lang_strings = scandir($default_lang);
$default_lang_strings = array_splice($default_lang_strings, 2); // remove . and ..
$translations_directorys = scandir($resources_dir);
$translations_directorys = array_splice($translations_directorys, 3); // remove . and .. and values/

$comments = [];

foreach($default_lang_strings as $file) {
    $resources[$file]['default']  =
        new SimpleXMLElement(
            file_get_contents($default_lang.$file)
        );

    $comments = array_merge($comments, get_comments(file_get_contents($default_lang.$file)));
    // Delete untranslatable strings
    error_reporting(E_ERROR | E_PARSE);
    foreach ($resources[$file]['default']->children() as $string) {
        if ($string['translatable'] == "false")
            unset($string[0][0]);
    }
    error_reporting(E_ALL);
}

foreach($translations_directorys as $directory) {
    foreach($resources as $key => $resource){
        $file_address = $resources_dir.$directory."/".$key;
        if (file_exists($file_address)){
            $lang = str_replace("values-", "", $directory);
            $resources[$key][$lang]  =
                simplexml_load_string(
                    file_get_contents($file_address)
                );/*            $resources[$key][$lang]  =
                new SimpleXMLElement(
                    file_get_contents($file_address)
                );*/
        }
    }
}

//echo Locale::getDisplayLanguage($key, "en")
//print_r($resources);



$strings = new SimpleXMLElement($xmlstr);

//echo find_by_name($resources["error_strings.xml"]['default'], 'connection_suspended_error')


//$strings = $strings->resources;

/*foreach ($strings as $string) {
    print_r($string);
    echo $string;
    echo $string['name'];
}*/


?>