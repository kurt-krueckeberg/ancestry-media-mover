<?php
declare(strict_types=1);
use RootsMagic\{FileReader, FileMover, MediaCopier};

include 'vendor/autoload.php';

locale_set_default('de_DE');

//$ancestry_folder = "~/d/genealogy/roots-magic-09-06-2023_media/";

$mediaCopier = new MediaCopier("/home/kurt/d/genealogy/roots-magic/sep-14-2023_media",
                         "/home/kurt/temp/ancestry-media");

$file = new FileReader('output.txt');

foreach ($file as $no => $line)  
     $mediaCopier($line);


