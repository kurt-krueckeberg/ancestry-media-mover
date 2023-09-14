<?php
declare(strict_types=1);
use RootsMagic\{FileReader, FileMover, MediaExtractor};

include 'vendor/autoload.php';

locale_set_default('de_DE');

//$ancestry_folder = "~/d/genealogy/roots-magic-09-06-2023_media/";

$filesHandler = new MediaExtractor("/home/kurt/Documents/genealogy//home/kurt/Documents/genealogy",
                         "~/temp/roots-magic-09-06-2023_media/");

$file = new FileReader('output.txt');

foreach ($file as $no => $line) {
 
     $filesHandler($line);
}

