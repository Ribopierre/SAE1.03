<?php 

// Lire le fichier regions.conf
// $regions=fopen("regions.conf","r");

$regions = file("regions.conf");

// Boucler sur chaque ligne
foreach($regions as $lignes)
{
    $tabTexte ="";
  $tabTableau ="";
  $tabComm ="";
   $tabData = explode(",", $lignes);
   print_r($tabData);
   $iso = $tabData[0];
   $nomRegion = substr($tabData[1], 1);
   $nomRegion = substr($nomRegion, 0, -1);
   $pop = $tabData[2];
   $superficie = $tabData[3];
   $nb_dept = $tabData[4];
   $texte = file("$nomRegion/texte-$iso.dat");
   $tableau = file("$nomRegion/tableau-$iso.dat");
   $comm = file("$nomRegion/comm-$iso.dat");
   $qrcode = file("qrcode/$iso.png");
  foreach($texte as $l_texte)
    {
      $tabTexte = $tabTexte.$l_texte;
    }
  foreach($tableau as $l_tableau)
    {
      $tabTableau = $tabTableau.$l_tableau;
    }
  foreach($comm as $l_comm)
    {
      $tabComm= $tabComm.$l_comm;
    }
  foreach($qrcode as $l_qrcode)
    {
      $tabQrcode= $tabQrcode.$l_qrcode;
    }

   // récup l'ISO

   // parcourir le fichier texte-ISO.dat

   // insérer les valeurs dans le template

   // créer le fichier tableau.dat avec le nom tableau-ISO.dat
   // parcourir le fichier tableau-ISO.dat
   // insérer les valeurs dans le template
   // créer le fichier comm.dat avec le nom comm-ISO.dat
   // parcourir le fichier comm-ISO.dat
   // insérer les valeurs dans le template
   // récup le nom
   // le foutre dans le H1

      
      
   //lignes à définir !!
      // récup l'ISO
      

      // parcourir le fichier texte-ISO.dat

      // insérer les valeurs dans le template

      // créer le fichier tableau.dat avec le nom tableau-ISO.dat

      // parcourir le fichier tableau-ISO.dat
      // insérer les valeurs dans le template

      // créer le fichier comm.dat avec le nom comm-ISO.dat

      // parcourir le fichier comm-ISO.dat
      // insérer les valeurs dans le template

      // récup le nom
      // le foutre dans le H1


   //echo $text;


   $content ="<html lang=\"en\">
   <head>
      <meta charset=\"UTF-8\">
      <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
      <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
      <title>$nomRegion</title>
      <link rel=\"stylesheet\" href=\"../sample-style-to-pdf.css\">
   </head>
   <body>
      <!-- Page  -->

      <section>
         <h1>Nom de la région : $nomRegion</h1>
            <article>
               <p>Population (en million) : <p> $pop</p>
               <p>Superficie (en km2) : </p><p> $superficie</p>
               <p>Nombre de départements : </p><p> $nb_dept</p>
               <img src=\"../images/$iso.png\" alt=\"Logo de la région\">
            </article>
         <div class=\"bottom\">Pied de page 1</div>
      </section>
      <!-- Page 1 -->
      <section>
         <div class=\"page2\">
            <h2>Résultats trimestriels</h2><h2>XX-YYY</h2>
            <article>
               <p class=\"resultat\">
                  $tabTexte
               </p>
    
               $tabTableau
            </article>
         </div>
         <div class=\"bottom\">Pied de page 2</div>
      </section>
      <!-- Page 2 -->
      <section >
         <article>
            $tabComm
         </article>
         <div class=\"bottom\">Pied de page 3</div>
      </section>
      <!-- Page 2 -->
      <section>
         <article>
            <p>https://bigbrain.biz/<code_region></p>
            <img src=\"\" alt=\"QRcode\">
         </article>
         <div class=\"bottom\">Pied de page 4</div>
      </section>
   </body>
   </html>";

  if ((file_exists("page_html")) == true) {
        rmdir("page_html");
    }
  mkdir("page_html");
  $fp=fopen("page_html/$iso.html", "w+");
  fwrite($fp, $content);
  fclose($fp);
  
}
?>
