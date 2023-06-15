#!/usr/bin/php
<?php
$files = glob('*.txt');
foreach ($files as $nom_fichier) {
    $nom_dir = trim(substr($nom_fichier, 0, strrpos($nom_fichier, '.')),"\r\n");

    if ((file_exists($nom_dir)) == false) {
        mkdir($nom_dir);
    }

    if ((file_exists("$nom_dir/texte.dat"))) {
        unlink("$nom_dir/texte.dat");
    } else {
        touch("$nom_dir/texte.dat");
    }

    if ((file_exists("$nom_dir/tableau.dat"))) {
        unlink("$nom_dir/tableau.dat");
    } else {
        touch("$nom_dir/tableau.dat");
    }

    if ((file_exists("$nom_dir/comm.dat"))) {
        unlink("$nom_dir/comm.dat");
    } else {
        touch("$nom_dir/comm.dat");
    }



    $f_c = fopen($nom_fichier, "r");
    $content = file($nom_fichier);

    /* texte.dat */
    $fichier_contents = file_get_contents($nom_fichier);


    preg_match_all('/[Dd][Eeé][Bb][Uu][Tt]_[Tt][Ee][Xx][Tt][Ee](.*?)[Ff][Ii][Nn]_[Tt][Ee][Xx][Tt][Ee]/us', $fichier_contents, $correspond, PREG_SET_ORDER);

    $para = 0;
    foreach ($content as $ligne) {
        if (preg_match("/CODE[=:]/i", $ligne)) {
            $iso = rtrim(strtoupper(substr($ligne, 5))," \n\r");
        }

        $ligne = rtrim($ligne);
        if (preg_match("/SOUS_TITRE=/i", $ligne)) {
            $sousTitre = substr($ligne, 11);
            file_put_contents("$nom_dir/texte.dat", "<h3>" . $sousTitre . "</h3>\n", FILE_APPEND);
        } elseif (preg_match("/TITRE=/i", $ligne)) {
            $titre = substr($ligne, 6);
            file_put_contents("$nom_dir/texte.dat", "<h2>" . $titre . "</h2>\n", FILE_APPEND);
        } elseif (preg_match("/[Dd][Eeé][Bb][Uu][Tt]_[Tt][Ee][Xx][Tt][Ee]/u", $ligne)) {
            file_put_contents("$nom_dir/texte.dat", "<p>" . $correspond[$para][1] . "</p>\n", FILE_APPEND);
            $para = $para + 1;
        }
    }

    /* tableau.dat */
    $header_tab = "<table>\n
                        \t<tr>\n
                        \t\t<th>Nom du produit</th>\n
                        \t\t<th>Ventes du trimestre</th>\n
                        \t\t<th>Chiffre d’affaires du trimestre</th>\n
                        \t\t<th>Ventes du même trimestre année précédente</th>\n
                        \t\t<th>CA du même trimestre année précédente</th>\n
                        \t\t<th>Evolution de CA en %age et en valeur absolue</th>\n
                    \t</tr>\n";


    preg_match("/[Dd][Eeé][Bb][Uu][Tt]_[Ss][Tt][Aa][Tt][Ss](.*?)[Ff][Ii][Nn]_[Ss][Tt][Aa][Tt][Ss]/us", $fichier_contents, $stats);
    file_put_contents("stats.tmp", $stats[1]);
    $tabS = file("stats.tmp");
    unset($tabS[0]);

    file_put_contents("$nom_dir/tableau.dat", $header_tab);
    foreach ($tabS as $lig) {
        $lig = rtrim($lig);
        $tablo = explode(",", $lig);
        file_put_contents("$nom_dir/tableau.dat", "\t<tr>\n", FILE_APPEND);

        $evo = (($tablo[2] - $tablo[4]) / $tablo[2]) * 100;
        $evo = round($evo);

        foreach ($tablo as $cel) {
            file_put_contents("$nom_dir/tableau.dat", "\t\t<td>$cel</td>\n", FILE_APPEND);
        }
        if ($evo > 0) {
            $val_abs = abs($evo);
            file_put_contents("$nom_dir/tableau.dat", "\t\t" . '<td class="vert">' . "$val_abs</td>\n", FILE_APPEND);
        } elseif ($evo < 0) {
            $val_abs = abs($evo);
            file_put_contents("$nom_dir/tableau.dat", "\t\t" . '<td class="rouge">' . "$val_abs</td>\n", FILE_APPEND);
        } else {
            $val_abs = abs($evo);
            file_put_contents("$nom_dir/tableau.dat", "\t\t<td>$val_abs</td>\n", FILE_APPEND);
        }
        file_put_contents("$nom_dir/tableau.dat", "\t</tr>\n", FILE_APPEND);
    }
    file_put_contents("$nom_dir/tableau.dat", "</table>\n", FILE_APPEND);

    /* comm.dat */
    file_put_contents("$nom_dir/comm.dat", "<h2>" . "Nos meilleurs vendeurs du trimestre" . "</h2>\n", FILE_APPEND);
    file_put_contents("$nom_dir/comm.dat", "<ul>\n", FILE_APPEND);

    foreach ($content as $ligne) {
        if (preg_match("/MEILLEURS[=:]/i", $ligne)) {
            $ventes = $ligne;
        }
    }


    foreach ($content as $ligne) {
        $ligne = rtrim($ligne);
        if (preg_match("/MEILLEURS[=:]/i", $ligne)) {
            $meilleur = substr($ligne, 10);
          echo "$ligne" . "\n";
          
        }
    }

    $tabM = explode(",", $meilleur);

    file_put_contents('meilleurs.tmp', "");
    foreach ($tabM as $lig) {
        $lig = rtrim($lig);
        file_put_contents("meilleurs.tmp", $lig . "\n", FILE_APPEND);

        $meilleurs = file('meilleurs.tmp');

        foreach ($meilleurs as $key => $lig) {
            $tabM2[$key] = preg_split("/[\/\ \=]/", $lig, -1, PREG_SPLIT_NO_EMPTY);
            $tabCode = explode("/", $lig);
            $code[$key] = $tabCode[0];
            $avatar[$key] = "pp/".strtolower($tabCode[0]).".png";
        }
       
    }  
    print_r($code);
    print_r($avatar);
    $tabMeilleurs = array();
    $premier = array(0);
    $deux = array(0);
    $trois = array(0);

    for ($i = 0; $i < count($tabM2); $i++) {
        $CA = intval($tabM2[$i][3]);
        if ($CA > $premier[0]) {
            $trois = $deux;
            $deux = $premier;
            $premier = array($CA, $tabM2[$i][1], $tabM2[$i][3]);
        } elseif ($CA > $deux[0]) {
            $trois = $deux;
            $deux = array($CA, $tabM2[$i][1], $tabM2[$i][3]);
        } elseif ($CA > $trois[0]) {
            $trois = array($CA, $tabM2[$i][1], $tabM2[$i][3]);
          print_r($tabM2[$i][1]);
        }
    }
    $tabMeilleurs = array($premier, $deux, $trois);
  //print_r($tabMeilleurs);
  $firstTwo = substr("$tabMeilleurs", 2);
  strtolower($firstTwo);
  print_r($firstTwo);
  //$dir = opendir('../pp');
    //while ($file = readdir($dir)) {
      //  strpos($file, $firstTwo);
      //    }
    //closedir($dir);
  
  // TODO mettre $tabMeilleurs[1] avec strtolower et recup les 2 premiers caractères
  // Associer les 2 premier caractère avec les nom de svg

    file_put_contents("$nom_dir/comm.dat", "<div id=\"premier>\"", FILE_APPEND);
    file_put_contents("$nom_dir/comm.dat", "\<li\>" . $tabMeilleurs[0][1] . "\</li\>", FILE_APPEND);
    file_put_contents("$nom_dir/comm.dat", "\<li\>" . $tabMeilleurs[0][2] . "\</li\>", FILE_APPEND);
    file_put_contents("$nom_dir/comm.dat", "</div>\"", FILE_APPEND);


    file_put_contents("$nom_dir/comm.dat", "<div id=\"deux>\"", FILE_APPEND);
    file_put_contents("$nom_dir/comm.dat", "\<li\>" . $tabMeilleurs[1][1] . "\</li\>", FILE_APPEND);
    file_put_contents("$nom_dir/comm.dat", "\<li\>" . $tabMeilleurs[1][2] . "\</li\>", FILE_APPEND);
    file_put_contents("$nom_dir/comm.dat", "</div>\"", FILE_APPEND);

    file_put_contents("$nom_dir/comm.dat", "<div id=\"trois>\"", FILE_APPEND);
    file_put_contents("$nom_dir/comm.dat", "\<li\>" . $tabMeilleurs[2][1] . "\</li\>", FILE_APPEND);
    file_put_contents("$nom_dir/comm.dat", "\<li\>" . $tabMeilleurs[2][2] . "\</li\>", FILE_APPEND);
    file_put_contents("$nom_dir/comm.dat", "</div><img src=\"\"></img>", FILE_APPEND);


    file_put_contents("$nom_dir/comm.dat", "<\ul>\n", FILE_APPEND);




    if ((file_exists("$nom_dir/texte-$iso.dat"))) {
        unlink("$nom_dir/texte-$iso.dat");
    } else {
        touch("$nom_dir/texte.dat");
    }

    if ((file_exists("$nom_dir/tableau-$iso.dat"))) {
        unlink("$nom_dir/tableau-$iso.dat");
    } else {
        touch("$nom_dir/tableau.dat");
    }

    if ((file_exists("$nom_dir/comm-$iso.dat"))) {
        unlink("$nom_dir/comm-$iso.dat");
    } else {
        touch("$nom_dir/comm.dat");
    }

    rename("$nom_dir/texte.dat", "$nom_dir/texte-$iso.dat");
    rename("$nom_dir/tableau.dat", "$nom_dir/tableau-$iso.dat");
    rename("$nom_dir/comm.dat", "$nom_dir/comm-$iso.dat");
    echo "$nom_dir/texte-$iso.dat";

    fclose($f_c);
}
?>