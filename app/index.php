<?php


spl_autoload_register(function ($class) {
  include 'lib/' . $class . '.php';
});

$cr = new RedirectRulesCreator();
$cr->setConfiguration(array(
  "pathToCSV" => "data/data.csv",
  "srcStringsToReplace" => "",
  "srcStringsReplaceWith" => "",
  "delimiter" => ";",
  "showComment" => false)
);

echo $cr->parseData('html');







