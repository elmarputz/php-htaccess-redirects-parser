<?php

// Or, using an anonymous function as of PHP 5.3.0
spl_autoload_register(function ($class) {
  include 'lib/' . $class . '.php';
});

$cr = new RedirectRulesCreator();
$cr->setConfiguration(array(
  "pathToCSV" => "data/data.csv",
  "srcStringsToReplace" => "",
  "srcStringsReplaceWith" => "",
  "delimiter" => ";")
);

echo $cr->parseData('html');







