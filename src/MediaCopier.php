<?php
declare(strict_types=1);
namespace RootsMagic;

class MediaCopier {

  private string $media_file;
  private string $src_dir;
  private string $dest_dir;
  //--static private string $regexName = "@^OwnerName\s+=\s+([,A-ZÖÄÜ][^,]+),\s*([^-]+)-@";

  static private string $regexName = '/^OwnerName\s+=\s+(,|[^,]+,)\s*([^-]+)-/';

  public function __construct(string $srcDir, string $destDir)
  {
      $this->media_file = '';

      if (is_dir($srcDir) === false)
          throw new \ErrorException("The directory $srcDir does not exist.");

      $this->src_dir = $srcDir;

      if (is_dir($destDir) === false)
          throw new \ErrorException("The directory $destDir does not exist.");

      $this->dest_dir = $destDir;
  }

  public function __invoke(string $line)
  {
    if ($line[5] == 'F') {// 'MediaFile' test

      $this->media_file = substr($line, 12);

      if ($this->media_file == '4118575_00278.jpg')

          $debug = 10;
  
    // Test for "OwnerName".
    } else if ($line[5] == 'N') {

      // Choose substring where the surname begins
      $rc = preg_match(self::$regexName, $line, $matches);
      
      $fullTargetFolder = $this->createTargetFolderName($matches[1], $matches[2]); 

      if (!is_dir($fullTargetFolder)) 

         $rc = mkdir($fullTargetFolder, 0777);

      $destFullFilename = $fullTargetFolder . '/' . str_replace(' ', '-', $this->media_file);

      if (!file_exists($destFullFilename)) {

        $srcFullFilename = $this->src_dir . "/" . $this->media_file;
  
        $rc = \copy($srcFullFilename, $destFullFilename);
        
        if (!$rc)
           echo "There was an error in copying $srcFilename to $destFilename\n";
      }
    }
  }
  
  // Returns full path to subdir: parent/subdir
  private function createTargetFolderName(string $surName, string $givenName) : string
  {
    if ($surName[0] == ',') {

      $subdir = "UnknownSurname";

    } else {

      $surName = rtrim($surName, ",");

      $subdir = ucfirst(strtolower($surName)) . "-" . str_replace(' ', '-', $givenName);     
    }
       
    $fullTargetPath = $this->dest_dir . "/" . $subdir;

     return $fullTargetPath;
  }
}