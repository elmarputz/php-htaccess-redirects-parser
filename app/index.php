<?php

// Or, using an anonymous function as of PHP 5.3.0
spl_autoload_register(function ($class) {
  include 'lib/' . $class . '.php';
});

$cr = new RedirectRulesCreator();
$cr->setConfiguration(array(
  "pathToCSV" => "custom_data/realurl-redirects-20160523.csv",
  "srcStringsToReplace" => "",
  "srcStringsReplaceWith" => "",
  "delimiter" => ";")
);

echo $cr->parseData('html');







