<?php
declare(strict_types=1);
use RootsMagic\{FileReader, FileMover, MediaCopier};

include 'vendor/autoload.php';

locale_set_default('de_DE');

$mediaCopier = new MediaCopier("/home/kurt/d/genealogy/roots-magic/sep-14-2023_media",
                         "/home/kurt/temp/ancestry-media");

$file = new FileReader('output.txt');

foreach ($file as $no => $line)  
     $mediaCopier($line);