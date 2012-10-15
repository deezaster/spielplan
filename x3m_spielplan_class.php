<?php
   // --- Test x3m_Spielplan() -------------------------------------------------
   $teams_cnt     = 12;
   $spielplan     = x3m_Spielplan::build($teams_cnt, date("Ymd"), 7, true);
   
   $spieltage_cnt = count($spielplan);
   echo "<h1>Spielplan</h1>";
   echo "Teams: <b>"     . $teams_cnt     . "</b> ";
   echo "Spieltage: <b>" . $spieltage_cnt . "</b><br>";

   for ($x = 1; $x <= $spieltage_cnt; $x++) {
      echo "<br><b>" . $x . ". Spieltag</b> " . $spielplan[$x]['datum'] . ": ";

      $spiele_cnt = count($spielplan[$x]) - 1;
      $spieltag   = $spielplan[$x];
      for ($y = 0; $y < $spiele_cnt; $y++) {
         echo "<b>".$spieltag[$y]['h'] . "-" . $spieltag[$y]['a'] . "</b> ";
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
   * @version    1.0 für PHP5
   * @author     Andy Theiler <andy@x3m.ch>
   * @copyright  Copyright (c) 1996 - 2007, Xtreme Software GmbH, Switzerland (www.x3m.ch)
   * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
   */
    abstract class x3m_Spielplan {

      public static function build ($teams = 4, $start_date = 20070101, $interval = 7, $mit_rueckrunde = true) {
      
         // nur gerade Anzahl Teams erlaubt
         if (($teams % 2) != 0) { return false; }
   
   
         // --- Spielpaarungen bestimmen ---------------------------------------
         $n      = $teams - 1;
         $spiele = array();
   
         for ($i = 1; $i <= $teams - 1; $i++) {
            $h = $teams;
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
   
            for ($k = 1; $k <= (($teams / 2) - 1); $k++) {
   
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
                  $a = $h;
                  $h = $temp;
               }
   
               $spiele[] = array('h' => $h, 'a' => $a, 'spieltag' => $i);
            }
         }
   
   
         // --- mit Rückrunde? -------------------------------------------------------
         if ($mit_rueckrunde) {
   
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
   
         $start_date = strtotime($start_date);
         $game_date  = date("Ymd", mktime(0, 0, 0, date("m", $start_date) ,
                                                   date("d", $start_date)+$interval,
                                                   date("Y", $start_date)));
   
         for ($x = 1; $x <= count($spielplan); $x++) {
            $spielplan[$x]['datum'] = $game_date;
            $game_date              = strtotime($game_date);
            $game_date              = date("Ymd", mktime(0, 0, 0, date("m", $game_date) ,
                                                                  date("d", $game_date)+$interval,
                                                                  date("Y", $game_date)));
         }
   
         return $spielplan;
      }
   }

?>