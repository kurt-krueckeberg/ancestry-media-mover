<?php
declare(strict_types=1);
namespace RootsMagic;

class MediaExtractor {

  private string $media_file;
  private string $surname;
  private string $givenNames;
  private  $file_mover;

  public function __construct(callable $func)
  {
      $this->media_file = '';
      $this->file_mover = $func;
  }

  private function process(string $name)
  {
     $comma_pos = strpos($name, ',');
 
     $surname = strtolower(substr($name, 0, $comma_pos));
     
     $surname = ucfirst($surname);

     // + 2 enables us to skip over ", "
     $given = substr($name, $comma_pos + 2); 
     
     ($this->file_mover)($this->media_file, $surname, $given);
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
