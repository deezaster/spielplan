Spielplan generieren mit dem Kantenfärbungs-Algorithmus
=======================================================

Implementationen
----------------


- **PHP4**: `x3m_spielplan_fnc.php`

- **PHP5**: `x3m_spielplan_class.php`


Einleitung
----------

Einen Spielplan (zB. Spielpaarungen für eine Fussballliga mit Hin- und Rückrunde) zu generieren ist doch eigentlich eine einfache Anforderung. Hab' ich auch gemeint! Trotz stundenlangen Analysen und diversen Skizzen ist es mir nicht gelungen, ein allgemein gültiges Muster zu erkennen. Zu meiner Entlastung sei zu bemerken, dass ich kein Mathematiker bin.

Um so fröhlicher war ich, als ich beim "googlen" auf die Webseite: <a href="http://www-i1.informatik.rwth-aachen.de/%7Ealgorithmus/liste.php">Algorithmus der Woche</a> gestossen bin. Dank dem <a href="http://www-i1.informatik.rwth-aachen.de/%7Ealgorithmus/algo36.php" >Kantenfärbungs Algorithmus</a> von Juniorprof. Dr. Sigrid Knust konnte ich mein Vorhaben doch noch realisieren.

Beschreibung
-------------
Der Aufruf der statischen Methode build() erstellt gemäss den Parameter (Anzahl Teams, mit oder ohne Rückrunde...etc.) den gewünschten Spielplan:

```php
$Spielplan = x3m_Spielplan::build($intTeams = 4,
                                  $intStartDate = 20070101,
                                  $intDayOffset = 7,
                                  $blnSecondRound = true);
```


PARAMETER |	BESCHREIBUNG
--------- | ------------
$intTeams | Anzahl Teams (Spieler)
$intStartDate | Datum der 1. Paarung
$intDayOffset | Tage zwischen den Spielpaarungen
$blnSecondRound | true (default) = mit Rückrunde / false = Paarungen nur für die Vorrunde

 

Der erstellte Spielplan wird als Array() zurückgeliefert:

 
```php
Spielplan Array (
   [1] => Spieltag 1 (
          [0] => Paarung 1 (
                 [h] => 1  = Heimmannschaft 1
                 [a] => 4  = Auswärtsmannschaft 4
          )
          [1] => Paarung 2 (
                 [h] => 2  = Heimmannschaft 2
                 [a] => 3  = Auswärtsmannschaft 3
          )
          [datum] => 20070611  = Datum Spieltag
   )
   [2] => Spieltag 2 (
          [0] => Paarung 1 (
                 :
          )
          [1] => Paarung 2 (
                 :
          )
          [datum] => 20070618  = Datum Spieltag
   )
   :
)
```

Code Beispiel
-------------

Spielplan für 4 Mannschaften generieren (mit Rückrunde):

```php
$teams_cnt     = 12;  
$spielplan     = x3m_Spielplan::build($teams_cnt, date("Ymd"), 7, true);  
$spieltage_cnt = count($spielplan);  
 
echo "Teams: <b>"     . $teams_cnt     . "</b> ";  
echo "Spieltage: <b>" . $spieltage_cnt . "</b><br>";  
  
for ($x = 1; $x <= $spieltage_cnt; $x++) 
{  
   echo "<br><b>" . $x . ". Spieltag</b> " . $spielplan[$x]['datum'] . ": ";  
  
   $spiele_cnt = count($spielplan[$x]) - 1;  
   $spieltag   = $spielplan[$x];  
   for ($y = 0; $y < $spiele_cnt; $y++) {  
      echo "<b>".$spieltag[$y]['h'] . "-" . $spieltag[$y]['a'] . "</b> ";  
   }  
}  
```

Dieses Beispiel liefert folgendes Ergebnis:



Teams: **4** Spieltage: **6**

**1. Spieltag** 20120611: 1-4 2-3  
**2. Spieltag** 20120618: 4-2 3-1  
**3. Spieltag** 20120625: 3-4 1-2  
**4. Spieltag** 20120702: 4-1 3-2  
**5. Spieltag** 20120709: 2-4 1-3  
**6. Spieltag** 20120716: 4-3 2-1  

