<?php


spl_autoload_register(function ($class) {
  include 'lib/' . $class . '.php';
});

$cr = new RedirectRulesCreator();
$cr->setConfiguration(array(
  "pathToCSV" => "data/redirects.csv",
  "srcStringsToReplace" => "www.schachermayer",
  "srcStringsReplaceWith" => "http://www.schachermayer",
  "delimiter" => ",",
  "newLineChar" => PHP_EOL, 
  "showComment" => false)
);

// echo $cr->parseData('html');
file_put_contents ('data/output.txt' , $cr->parseData('html'));






