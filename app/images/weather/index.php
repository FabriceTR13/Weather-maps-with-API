<?php
$ext_list = Array("jpg", "jpeg", "bmp", "gif", "png"); // Liset des extensions de photo
$listephotos = Array();
$dossier = opendir("."); // Ouvre le dossier courant
for($i=0; $f = readdir($dossier); $i++){
if(is_file($f)){ // Si c'est un fichier
if(in_array(preg_replace("#(.+)\.(.+)#", "$2", $f), $ext_list)){ // Si c'est une photo
$listephotos[$i] = $f; // Ajoute la photo
}
}
}
closedir($dossier); // On n'a plus besoin du dossier
sort($listephotos); // Trie par ordre alphabétique
// Et maintenant, on affiche.
foreach($listephotos as $nom){
echo $nom."<br><img src='".$nom."'><br><br>"; // Le nom de la photo + un retour à la ligne
}
exit;
?>

<?php

header("location: http://en.previmeteo.com/professionals/google-weather-api.php");
exit;
?>
