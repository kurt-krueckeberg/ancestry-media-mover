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
          throw \ErrorException("The directory $srcDest does not exist.");

      $this->src_dir = $srcDir;

      if (is_dir($destDir) === false)
          throw \ErrorException("The directory $destDir does not exist.");

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

      // todo: What is surname is missing?
      $fullpath = $this->createSubfolder($person_name); 

      // todo: This needs more work: The source media file might have embedded spaces.
      // These need to be removed or changed.
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

     $given = str_replace($given, ' ', '-');

     $subdir = $surname . "-" . $given; 

     $fullpath = $this->dest_dir . "/" . $subdir;

     // todo: The destination subdir parent needs to be $this->destDir.
     if (!is_dir($newDir)) {

        $rc = mkdir($newDir, 0777);

        if ($rc == false)
           $debug = 10;
     } 

     return $fullpath;
  }

  // Todo: Change to a more specific task: 
  private function copy(string $destFullpath, string $srcFile)
  {
     $destFilename = $this->dest_dir . "/" . str_replace($srcFile, ' ', '-');

     if (!file_exists($destFilename))  {

         $fromFilename = "'" . $this->src_dir . $srcFile . "'";

         echo "From = $fromName | Dest = $destName\n";

         $rc = copy($fromFilename, $destFilename);
     } 
  }
}
