<?php

declare(strict_types=1);

/**
 * read csv file with redirects and generate redirect match rules in the format
 * "RedirectMatch 301 /relative/path/to/page https://mydomain.com/path/to/target/"
 */
class RedirectRulesCreator
{

  /**
   * @var string path to csv file
   */
  private $pathToCSV = "data/data.csv";
  /**
   * @var array strings to replace in source url
   */
  protected $srcStringsToReplace = array("http://www.mydomain.at", "http://www.myotherdomain.at");
  /**
   * @var string replacement string used for replacement in source urls
   */
  protected $srcStringsReplaceWith = "";
  /**
   * @var string delimiter in CSV file
   */
  protected $delimiter = ";";

  /**
   * @var string redirectRule as formatted string (printf)
   * example: 'RedirectMatch 301 %s %s'
   */
  protected $redirectRule = "";


  /**
   * RedirectRulesCreator constructor.
   * @param array $config - key / value pairs for configuration variables
   */
  public function setConfiguration(array $config) {
    $this->pathToCSV = $config['pathToCSV'] ?? null;
    $this->srcStringsToReplace = $config['srcStringsToReplace'] ?? array("http://www.mydomain.at", "http://www.myotherdomain.at");
    $this->srcStringsReplaceWith = $config['srcStringsReplaceWith'] ?? "  ";
    $this->delimiter = $config['delimiter'] ?? ",";
    $this->redirectRuleOutput = $config['redirectRuleOutput'] ?? 'RedirectMatch 301 %s %s';
  }


  /**
   * @param string output format
   * @return string
   *
   */
  public function parseData(string $format = 'html'):string
  {
    $row = 1;
    $retval = "";
    if (($handle = fopen($this->pathToCSV , "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, $this->delimiter)) !== FALSE) {
        $num = count($data);
        $row++;
        for ($c = 0; $c < $num; $c++) {

          $srcStrg = str_replace($this->srcStringsToReplace, $this->srcStringsReplaceWith, $data[0]);
          $targetStrg = $data[1];
          $retval .= sprintf($this->redirectRuleOutput, $srcStrg, $targetStrg);

          if ($format == 'html')
            $retval .= "<br />\n";
        }
      }
      fclose($handle);
    }
    return $retval;
  }
}