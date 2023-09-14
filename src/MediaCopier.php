<?php
declare(strict_types=1);
namespace RootsMagic;

class MediaCopier {

  private string $media_file;
  private string $src_dir;
  private string $dest_dir;

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

      $this->media_file = substr($line, 13);
  
    // Test for "OwnerName".
    } else if ($line[5] == 'N') {

      // Choose substring where the surname begins   
      $person_name = substr($line, 12, strpos(substr($line, 12), '-'));

      $fullpath = $this->createSubfolder($person_name); 

      $this->copy($fullpath, $this->media_file);
    }
  }
  // Returns full path to subdir: parent/subdir
  private function createSubfolder(string $name) : string
  {
     $comma_pos = strpos($name, ',');
 
     $surname = strtolower(substr($name, 0, $comma_pos));
     
     $surname = ucfirst($surname);

     // + 2 enables us to skip over ", "
     $given = substr($name, $comma_pos + 2); 

     $given = str_replace(' ', '-', $given);

     $subdir = $surname . "-" . $given; 

     $fullpath = $this->dest_dir . "/" . $subdir;

     if (!is_dir($fullpath)) {

        $rc = mkdir($fullpath, 0777);

        if ($rc == false)
           $debug = 10;
     } 

     return $fullpath;
  }

  private function copy(string $destFullpath, string $srcFile)
  {
     if (!file_exists($srcFile)) {
         echo "Cannot copy $srcFile. It does not exist.\n";
         return;
     }

     $destFilename = $this->dest_dir . "/" . str_replace(' ', '-', $srcFile);

     if (!file_exists($destFilename)) {

         $fromFilename = "'" . $this->src_dir . $srcFile . "'";

         echo "Copying $fromFilename to $destFilename\n";

         $rc = \copy($fromFilename, $destFilename);
     } 
  }
}
