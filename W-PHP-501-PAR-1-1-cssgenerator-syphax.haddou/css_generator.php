<?php

$tab = array();

// RECUPERE LES FICHIERS DU DOSSIER
function open($dir){      

    global $tab;

    if (is_dir($dir)){
        $dh = opendir($dir);
        while (($file = readdir($dh)) !== false){
          echo "$file\n"; 
            if(is_dir($dir . '/' . $file)){
              if($file != '.' && $file != '..'){
                open($dir . '/' . $file);
              }
              
                  
            }
            if (str_ends_with($file, '.png')) {
                $tab[] = $dir . '/' . $file;
            }    
        }
        closedir($dh);
    }
  }
  
  $dir = $argv[$argc-1];
  open($dir);
  print_r($tab);
  createimg($tab);

function createimg($tab) {

  $total_largeur = 0;
  $max_hauteur = 0;
  $dmarray = [];
  foreach ($tab as $value) {
    $image = imagecreatefrompng($value);
    $largeur = imagesx($image);
    $hauteur = imagesy($image);
    //$dmarray[] = ["image" => $image , "largeur" => $largeur, "hauteur" => $hauteur];
    $total_largeur = $total_largeur + $largeur;
    $max_hauteur = max($max_hauteur, $hauteur);

  }
  $background = imagecreatetruecolor($total_largeur, $max_hauteur);


  imagesavealpha($background, true);
  $rgb = imagecolorallocatealpha($background, 0, 0, 0, 127);
  imagefill($background, 0,0, $rgb);
  $copy = 0;
  
  foreach ($tab as $value){
    $image = imagecreatefrompng($value);
    $largeur = imagesx($image);
    $hauteur = imagesy($image);
    imagecopy($background, $image, $copy, 0,0,0, $largeur, $hauteur);
    $copy = $copy + $largeur;
  }
  imagepng($background, "sprite.png");
  echo "GOODD !!\n";

  $stream = fopen("generator.css" , "w");
  $copy = 0;

  foreach ($tab as $value){
    list($largeur, $hauteur) = getimagesize($value);
    $name = ".sprite-$value{
      width: $largeur"."px;
      height: $hauteur"."px;
      background-position: $copy"."px 0px;
    }\n";
    fwrite($stream, $name);
    $copy = $copy + $largeur;
}
}