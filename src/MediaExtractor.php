<?php
declare(strict_types=1);
namespace RootsMagic;

class MediaExtractor {

  private string $media_file;
  private string $surname;
  private string $givenNames;
  private string $src_dir;
  private string $dest_dir;

  public function __construct(string $srcDir, string $destDir)
  {
      $this->media_file = '';
      $this->src_dir = $srcDir;
      $this->dest_dir = $destDir;
  }

  // Todo: Change to a more specific task: 
  private function copy(string $fileName, string $surname, string $given)
  {
     $given = str_replace($given, ' ', '-');

     $subdir = $surname . "-" . $given; 

     $newDir = $this->dest_dir . "/" . $subdir;

     // todo: The destination subdir parent needs to be $this->destDir.
     if (!is_dir($newDir))
         mkdir($newDir, 0777);

     $destName = "'./$newDir/$fileName'"; // <-- Didn't help.

     echo $destName;
    
     if (!file_exists($destName))  {

         $fromName = "'" . $this->src_dir . $fileName . "'";

         echo "From = $fromName | Dest = $destName\n";

         $rc = copy($fromName, $destName);
     } 
  }

  private function process(string $name)
  {
     $comma_pos = strpos($name, ',');
 
     $surname = strtolower(substr($name, 0, $comma_pos));
     
     $surname = ucfirst($surname);

     // + 2 enables us to skip over ", "
     $given = substr($name, $comma_pos + 2); 

     $given = str_replace($given, ' ', '-');

     $subdir = $surname . "-" . $given; 

     $fullDestDir = $this->dest_dir . "/" . $subdir;
     
     // todo: This needs more work: The source media file might have embedded spaces.
     // These need to be removed or changed.
     $this->copy($fullDestDir, $this->media_file, $surname, $given);
  }

  public function __invoke(string $line)
  {
    if ($line[5] == 'F') {// 'MediaFile' test

      $this->media_file = substr($line, 13);
  
    // "OwnerName" test
    } else if ($line[5] == 'N') {

      // choose substring where the surname begins   
      $person_name = substr($line, 12, strpos(substr($line, 12), '-'));

      $this->process($person_name);     
    }
  }
}
