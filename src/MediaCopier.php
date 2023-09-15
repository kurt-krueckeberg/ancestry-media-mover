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
  
    // Test for "OwnerName".
    } else if ($line[5] == 'N') {

      // Choose substring where the surname begins
      $rc = preg_match(self::$regexName, $line, $matches);

      $person_name = substr($line, 12, strpos(substr($line, 12), '-'));

      $fullpathFolder = $this->createTargetFolderName($matches[1], $matches[2]); 

      if (!is_dir($fullpathFolder)) 

         $rc = mkdir($fullpathFolder, 0777);

      $this->copy($fullpathFolder, $this->media_file);
    }
  }
  // Returns full path to subdir: parent/subdir
  private function createTargetFolderName(string $surName, string $givenName) : string
  {
    if ($surName[0] == ',')

      $subdir = "UnknownSurname";

    else {

      $surName = rtrim($surName, ",");  

      $surname = strtolower($surName);
     
      $surname = ucfirst($surname);

      $subdir = $surname . "-" . $givenName;     
    }
    echo $subdir . "\n";
    
    $fullpath = $this->dest_dir . "/" . $subdir;

     return $fullpath;
  }
  private function copy(string $destFullpath, string $srcFile)
  {
    $srcFilename = $this->src_dir . "/" . $srcFile;

    if (!file_exists($srcFilename))  {

        echo "Cannot copy $srcFile. It does not exist.\n";
        return;
    }

    $tmp = ltrim($srcFile); // Remove any leading spaces in source file.

    $destFilename = $this->dest_dir . "/" . str_replace(' ', '-', $tmp);

    if (!file_exists($destFilename)) {

        $rc = \copy($srcFilename, $destFilename);

        if (!$rc)
           echo "There was an error in copying $srcFilename to $destFilename\n";
    } 
  }
}
