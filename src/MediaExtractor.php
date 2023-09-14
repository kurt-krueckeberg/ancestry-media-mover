<?php
declare(strict_types=1);
namespace RootsMagic;

class MediaExtractor {

  private string $media_file;
  private string $surname;
  private string $givenNames;
  private string $src_dir;

  public function __construct(string $srcDir)
  {
      $this->media_file = '';
      $this->src_dir = $srcDir;
  }

  // Todo: Are absolute paths required?
  private function copy(string $fileName, string $surname, string $given)
  {
     $dir = $surname . ", " . $given; 

     if (!is_dir($dir))
         mkdir($dir, 0777);

     $destName = "'./$dir/$fileName'"; // <-- Didn't help.
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
     
     $this->copy($this->media_file, $surname, $given);
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
