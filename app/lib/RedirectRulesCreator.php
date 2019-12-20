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
   * @var string new line rendering in output
   * example: '<br /> \n'
   */
  protected $newLine = "";

  /**
   * @var bool show source line as comment in output
   */
  protected $showComment = "";


  /**
   * RedirectRulesCreator constructor.
   * @param array $config - key / value pairs for configuration variables
   */
  public function setConfiguration(array $config) {

    $this->pathToCSV = $config['pathToCSV'] ?? ""; 
    $this->srcStringsToReplace = $config['srcStringsToReplace'] ?? array("http://www.mydomain.at", "http://www.myotherdomain.at");
    $this->srcStringsReplaceWith = $config['srcStringsReplaceWith'] ?? "  ";
    $this->delimiter = $config['delimiter'] ?? ",";
    $this->newLine = $config['newLineChar'] ?? "<br /> \n";
    $this->redirectRuleOutput = $config['redirectRuleOutput'] ?? 'RedirectMatch 301 %s %s';
    $this->showComment = (bool)$config['showComment'];

  }


  /**
   * @param string output format
   * @return string
   *
   */
  public function parseData(string $format = 'html') : string
  {
  
    $row = 1;
    $retval = "";
    if (($handle = fopen($this->pathToCSV , "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, $this->delimiter)) !== FALSE) {

      
        $num = count($data);
        $row++;
        for ($c = 0; $c < $num; $c++) {
          
          // check & parse querystring if it exists
          $urlWithQueryString = false;  
          
          $url = parse_url($data[0]);
          if (isset($url["query"])) {
            parse_str($url["query"], $queryString);
            $urlWithQueryString = true;
          }
        
          // complex query string redirects 
          if ($urlWithQueryString) {

            // relative path in source - take current hostname
            if (!isset($url['host'])) {
                $url['host'] = $this->getRealHost();
            }

            $srcStrg = "RewriteCond %{HTTP_HOST} ^".preg_quote($url['host'])."$ [NC]";
            $srcStrg .=  $this->newLine;
            // path 
            $srcStrg .= "RewriteCond %{REQUEST_URI} " . preg_quote($url['path']);

        
            foreach ($queryString as $qskey => $qsvalue) {
              $srcStrg .= $this->newLine;
              $srcStrg .= "RewriteCond %{QUERY_STRING} " . $qskey . "=" .preg_quote($qsvalue);
            }

        
            // target - '?' and 'QSD' (Apache 2.4 >) at the end makes sure, that the querystring is 
            // not applied at the end of the target url
             $srcStrg .= $this->newLine;
             $targetStrg = "RewriteRule .* " . preg_quote($data[1]) ."? [QSD,R=301,L]";
          }

          // simple redirect without query string 
          // use pattern in $this->redirectRuleOutput
          else {
            $srcStrg = str_replace($this->srcStringsToReplace, $this->srcStringsReplaceWith, $data[0]);
            $targetStrg = $data[1];
          }
        }

        if ((strlen($srcStrg) > 0) && (strlen($targetStrg) > 0)) {
          if ($this->showComment) {
            $retval .= '# ' . implode($this->delimiter, $data) . $this->newLine;
          }
          if (!$urlWithQueryString)
            $retval .= sprintf($this->redirectRuleOutput, $srcStrg, $targetStrg);
          else 
            $retval .= $srcStrg . $targetStrg;

          $retval .= $this->newLine;
        
      }

      }
      fclose($handle);
    }
    return $retval;
  }


  /**
   * return current hostname
   * @param bool removePort from string
   * @return string hostname
   *
   */
  private function getRealHost(bool $removePort = false) : string {
    if ($removePort)
      list($realHost,)=explode(':',$_SERVER['HTTP_HOST']);
    else 
      $realHost = $_SERVER['HTTP_HOST'];
    return $realHost;

  }

}