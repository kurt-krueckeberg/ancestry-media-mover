<?php
declare(strict_types=1);
use RootsMagic\{FileReader, FileMover, MediaExtractor};

include 'vendor/autoload.php';

locale_set_default('de_DE');

//$ancestry_folder = "~/d/genealogy/roots-magic-09-06-2023_media/";

$mediaExtractor = new MediaExtractor("~/d/genealogy/roots-magic-09-06-2023_media/",
                         "~/temp/ancestry-media");

$file = new FileReader('output.txt');

foreach ($file as $no => $line)  
     $filesHandler($line);


