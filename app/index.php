<?php

spl_autoload_register(function ($class) {
  include 'lib/' . $class . '.php';
});

$cr = new RedirectRulesCreator();
$cr->setConfiguration(array(
  "pathToCSV" => "custom_data/redirects-airfield.csv",
  "srcStringsToReplace" => "",
  "srcStringsReplaceWith" => "",
  "delimiter" => ";")
);

echo $cr->parseData('html');







