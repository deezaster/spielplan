<?php
   // --- Test x3m_Schedule() -------------------------------------------------
   $teams_cnt     = 4;
   $Spielplan     = x3m_Schedule($teams_cnt, date("Ymd"), 7, true);
   
   $spieltage_cnt = count($Spielplan);
   echo "<h1>Spielplan</h1>";
   echo "Teams: <b>"     . $teams_cnt     . "</b> ";
   echo "Spieltage: <b>" . $spieltage_cnt . "</b><br>";

   for ($x = 1; $x <= $spieltage_cnt; $x++) {
      echo "<br><b>" . $x . ". Spieltag</b> " . $Spielplan[$x]['datum'] . ": ";

      $spiele_cnt = count($Spielplan[$x]) - 1;
      $spieltag   = $Spielplan[$x];
      for ($y = 0; $y < $spiele_cnt; $y++) {
         echo $spieltag[$y]['h'] . "-" . $spieltag[$y]['a'] . " ";
      }
   }
   exit();
   
   

  /**
   * Spielplan generieren
   *
   * Spielplan generieren nach dem "Kantenfärbungs Algorithums".
   * Quelle: http://www-i1.informatik.rwth-aachen.de/~algorithmus/algo36.php
   *
   * ---------------------------------------------------------------------
   * Support/Info/Download: https://github.com/deezaster/spielplan
   * ---------------------------------------------------------------------
   *
   * @package    x3m
   * @version    1.0. für PHP4
   * @author     Andy Theiler <andy@x3m.ch>
   * @copyright  Copyright (c) 1996 - 2007, Xtreme Software GmbH, Switzerland (www.x3m.ch)
   * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
   */
    function x3m_Schedule($intTeams = 4, $intStartDate = 20070101, $intDayOffset = 7, $blnSecondRound = true) {

   
      // nur gerade Anzahl Teams erlaubt
      if (($intTeams % 2) != 0) { return false; }


      // --- Spielpaarungen bestimmen ---------------------------------------
      $n      = $intTeams - 1;
      $spiele = array();

      for ($i = 1; $i <= $intTeams - 1; $i++) {
         $h = $intTeams;
         $a = $i;
         // heimspiel? auswärtsspiel?
         if (($i % 2) != 0) {
            $temp = $a;
            $a    = $h;
            $h    = $temp;
         }

         $spiele[] = array('h'        => $h, 
                           'a'        => $a, 
                           'spieltag' => $i);

         for ($k = 1; $k <= (($intTeams / 2) - 1); $k++) {

            if (($i-$k) < 0) {
               $a = $n + ($i-$k);
            }
            else {
               $a = ($i-$k) % $n;
               $a = ($a == 0) ? $n : $a; // 0 -> n-1
            }

            $h = ($i+$k) % $n;
            $h = ($h == 0) ? $n : $h;    // 0 -> n-1

            // heimspiel? auswärtsspiel?
            if (($k % 2) == 0) {
               $temp = $a;
               $a    = $h;
               $h    = $temp;
            }

            $spiele[] = array('h' => $h, 'a' => $a, 'spieltag' => $i);
         }
      }


      // --- mit Rückrunde? -------------------------------------------------------
      if ($blnSecondRound) {

         $spiele_cnt = count($spiele);
         for ($x = 0; $x < $spiele_cnt; $x++) {

            $spiele[] = array('h'        => $spiele[$x]['a'],
                              'a'        => $spiele[$x]['h'],
                              'spieltag' => $spiele[$x]['spieltag'] + $n);
         }
      }

   
      // --- Spielplan erstellen --------------------------------------------------
      $spielplan  = array();
      $spiele_cnt = count($spiele);

      for ($x = 0; $x < $spiele_cnt; $x++) {

         $spielplan[$spiele[$x]['spieltag']][] = array('h' => $spiele[$x]['h'],
                                                       'a' => $spiele[$x]['a']);
      }

      $intStartDate = strtotime($intStartDate);
      $game_date    = date("Ymd", mktime(0, 0, 0, date("m", $intStartDate) ,
                                                  date("d", $intStartDate)+$intDayOffset,
                                                  date("Y", $intStartDate)));

      for ($x = 1; $x <= count($spielplan); $x++) {
         $spielplan[$x]['datum'] = $game_date;
         $game_date              = strtotime($game_date);
         $game_date              = date("Ymd", mktime(0, 0, 0, date("m", $game_date) ,
                                                               date("d", $game_date)+$intDayOffset,
                                                               date("Y", $game_date)));
      }

      return $spielplan;
   }

?>