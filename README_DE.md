# Chart_XH

Chart_XH ermöglicht das Einbetten von Diagrammen auf einer CMSimple_XH Website.
Einfache Diagramme können in der Pluginverwaltung erstellt und gewartet werden.
Für fortgeschritte Bedürfnisse gibt es die Möglichkeit sogenannte „Power-Charts“
zu verwenden. Die Diagramme werden mit [Chart.js](https://www.chartjs.org/)
dargestellt.

- [Voraussetzungen](#voraussetzungen)
- [Download](#download)
- [Installation](#installation)
- [Schnellstart](#schnellstart)
- [Einstellungen](#einstellungen)
- [Verwendung](#verwendung)
  - [Power-Charts](#power-charts)
  - [Manuelle Bearbeitung der Diagrammdateien](#manuelle-bearbeitung-der-diagrammdateien)
- [Fehlerbehebung](#fehlerbehebung)
- [Lizenz](#lizenz)
- [Danksagung](#danksagung)

## Voraussetzungen

Chart_XH ist ein Plugin für [CMSimple_XH](https://cmsimple-xh.org/de/).
Es benötigt CMSimple_XH ≥ 1.7.0 und PHP ≥ 8.0.0 mit der DOM Erweiterung.
Chart_XH benötigt weiterhin [Plib_XH](https://github.com/cmb69/plib_xh) ≥ 1.10;
ist dieses noch nicht installiert (see *Einstellungen*→*Info*),
laden Sie das [aktuelle Release](https://github.com/cmb69/plib_xh/releases/latest)
herunter, und installieren Sie es.

## Download

Das [aktuelle Release](https://github.com/cmb69/chart_xh/releases/latest)
kann von Github herunter geladen werden.

## Installation

Die Installation erfolgt wie bei vielen anderen CMSimple_XH-Plugins auch.

1. Sichern Sie die Daten auf Ihrem Server.
1. Entpacken Sie die ZIP-Datei auf Ihrem Rechner.
1. Laden Sie das ganzen Ordner `chart/` auf Ihren Server in das
   `plugins/` Verzeichnis von CMSimple_XH  hoch.
1. Machen Sie die Unterordner `config/`, `css/` und `languages/`
   beschreibbar.
1. Prüfen Sie unter `Plugins` → `Chart` im Administrationsbereich,
   ob alle Voraussetzungen erfüllt sind.

## Schnellstart

Im `chart/examples/` Ordner befindet sich das Beispieldiagramm `voting.xml`.
Kopiere diese Datei in den Inhaltsordner von CMSimple_XH (`content/chart/`).
Dann binde das Beispieldiagramm auf einer Seite ein:

    {{{chart('voting')}}}

Wechsle in den Ansichtsmodus, wo du ein Säulendiagramm der Ergebnisse der
Bundestagswahlen in drei Kategorien für die Jahre 2017, 2021 und 2025 siehst.
Nun gehe in die Plugin-Verwaltung (`Plugins` → `Chart` → `Verwaltung`), wähle
`voting` und drücke `Bearbeiten`. Dann siehst du ein Formular wo das Diagramm
bearbeitet werden kann. Fürs erste wollen wir nur die Gruppierung ändern, so dass
die Ergebnisse der einzelnen Parteien direkt nebeneinander angezeigt werden.
Dazu hake `transposed` an und drücke `Speichern`. Gehe wieder zur Seite wo das
Diagramm angezeigt wird, und du wirst den Unterschied sehen. Nun wollen wir das
Diagramm als Halbring anstelle eines Säulendiagramms anzeigen. Gehe wieder zum
Bearbeitungsformular, und wähle `Halbring` im `Typ` Dropdown-Menü. Speichern,
und zurück zur Diagrammanzeige. Sieht ein bisschen komisch aus, da drei Jahre
auf einmal dargestellt werden. Also wieder zurück zum Bearbeitungsformular,
und entferne dort die Beschriftungen der beiden jüngsten Wahlen (das
Beschriftungsfeld sollte nur noch `2017` enthalten). Speichern und das Diagramm
anschauen. So kennt man das.

Nun wieder zurück zum Bearbeitungsformular, wo du ein bisschen herumspielen kannst.
Vielleicht möchtest du einen anderen Diagrammtyp ausprobieren, oder die Farben
der Parteien ändern (dazu sollte es Farbwähler geben; falls nicht, dann erwäge
die Verwendung eines zeitgemäßen Browsers). Vielleicht willst du die Reihenfolge
der Datensätze ändern (dazu gibt es rechts Pfeilschalter), oder ein paar Datensätze
löschen (dazu gibt es rechts Mülleimerschalter), oder auch einen neuen Datensatz
anlegen (dazu gibt es einen Plusschalter oben in der Datensatztabelle). Und
vielleicht möchtest du die Werte ändern; das geht direkt im Textfeld, aber du
kannst auch den Bearbeitungschalter (Bleistift) nutzen, der dann numerische
Eingabefelder anzeigt (die machen es einfacher, da im Textfeld als Dezimaltrenner
der ungewohnte Punkt verwendet werden muss). Willst du Werte hinzufügen oder
entfernen, musst du das im Textfeld tun. Du kannst auch verschiedene Seitenverhältnisse
ausprobieren (die Breite des Diagramms ist immer 100%; das Seitenverhältnis
bestimmt die Höhe).

Abschließend möchtest du vielleicht ein eigenes Diagramm erstellen. Gehe wieder
in die Verwaltung (`Plugins` → `Chart` → `Verwaltung`), drücke `Neu` und
bearbeite das Diagramm wie zuvor. Der einzige Unterschied ist, dass du nun das
`Name` Feld ausfüllen musst, das deaktiviert ist, wenn du ein bestehendes Diagramm
bearbeitest. Danach kannst du das neue Diagramm anstelle des `voting` Beispiel-
Diagramms anzeigen indem du den Pluginaufruf entsprechend änderst.

## Einstellungen

Die Plugin-Konfiguration erfolgt wie bei vielen anderen
CMSimple_XH-Plugins auch im Administrationsbereich der Website.
Gehen Sie zu `Plugins` → `Chart`.

Sie können die Voreinstellungen von Chart_XH unter
`Konfiguration` ändern. Hinweise zu den Optionen werden beim
Überfahren der Hilfe-Icons mit der Maus angezeigt.

Die Lokalisierung wird unter `Sprache` vorgenommen. Sie können die
Sprachtexte in Ihre eigene Sprache übersetzen, falls keine
entsprechende Sprachdatei zur Verfügung steht, oder diese Ihren
Wünschen gemäß anpassen.

Das Aussehen von Chart_XH kann unter `Stylesheet` angepasst werden.

## Verwendung

Um ein Diagramm auf einer Seite einzubetten:

    {{{chart('name')}}}

Um ein Diagramm im Template einzubetten:

    <?=chart('name')?>

Dabei ist `name` der Name eines Diagramms, das zuvor in der Plugin-Verwaltung
angelegt wurde (der [Schnellstart Abschnitt](#schnellstart) erklärt wie das
gemacht wird).

### Power-Charts

Während normale Diagramme die wichtigste Funktionalität abdecken sollten, können
Power-Charts (fast) alle Möglichkeiten von Chart.js nutzen. Um diese zu verwenden,
benötigt man ein Grundverständnis von JSON, und muss die
 [Chart.js Dokumentation](https://www.chartjs.org/docs/latest/) lesen.

In der Pluginverwaltung wird das Erstellen und Warten von Power-Charts
grundlegend untersützt (einschließlich Syntax-Highlighting, wenn
[Codeeditor_XH ≥ 2.3](https://github.com/cmb69/codeeditor_xh/releases) installiert
ist), aber vermutlich ist es das Beste, wenn solche Diagramme auf einem lokalen
Rechner mit einem guten JSON Editor erstellt werden.

Im Wesentlichen geht es darum, dass eine JSON-Datei statt der üblichen JavaScript-
Konfiguration von Chart.js erstellt wird, und dann das Diagramm auf einer Seite
eingebettet wird:

    {{{chart_power('name', 'caption')}}}

Oder im Template:

    <?=chart_power('name', 'caption')?>

Da JSON nicht die vollständige JavaScript-Syntax unterstützt, ist es nicht möglich
Funktionen zu definieren, die als Ereignisbehandler oder anderweitig verwendet werden,
aber die Masse der Chart.js Konfiguration kann in JSON ausgedrückt werden.

Es ist zu beachten, dass normale Diagramme in der Pluginverwaltung als JSON
exportiert werden können, so dass es möglich ist Diagramme wie üblich zu definieren,
zu exportieren, und dann einfach ein paar Optionen hinzuzufügen, oder die
Diagramme anderweitig zu justieren.

### Manuelle Bearbeitung der Diagrammdateien

Werden Diagrammdateien manuell bearbeitet, wird empfohlen einen Editor mit
Unterstützung von RelaxNG-Schemata zu verwenden, und gegen `chart.rng` im Wurzelordner
des Plugins zu validieren. Wird das nicht getan, kann es passieren, dass die
Diagramme nicht geladen werden können. In diesem Fall kann der `Prüfen` Schalter
in der Pluginverwaltung genutzt werden, um herauszufinden, wo der Fehler liegt.

## Fehlerbehebung

Melden Sie Programmfehler und stellen Sie Supportanfragen entweder auf
[Github](https://github.com/cmb69/chart_xh/issues) oder im
[CMSimple_XH Forum](https://cmsimpleforum.com/).

## Lizenz

Chart_XH ist freie Software. Sie können es unter den Bedingungen der
GNU General Public License, wie von der Free Software Foundation
veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß
Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren Version.

Die Veröffentlichung von Chart_XH erfolgt in der Hoffnung, dass es
Ihnen von Nutzen sein wird, aber ohne irgendeine Garantie, sogar ohne
die implizite Garantie der Marktreife oder der Verwendbarkeit für einen
bestimmten Zweck. Details finden Sie in der GNU General Public License.

Sie sollten ein Exemplar der GNU General Public License zusammen mit
Chart_XH erhalten haben. Falls nicht, siehe <https://www.gnu.org/licenses/>.

Copyright © Christoph M. Becker

## Danksagung

Chart_XH wurde von *ChoseX* angeregt.

Das Plugin wird angetrieben von [Chart.js](https://www.chartjs.org/).
Vielen Dank für die Bereitstellung dieses großartigen Tools unter der MIT Lizenz!

Das Plugin-Icon wurde von [Vectors Market - Flaticon](https://www.flaticon.com/free-icons/graph) gestaltet.
Vielen Dank für die freie Verfügbarkeit dieses Icons.

Vielen Dank an die Community im
[CMSimple_XH-Forum](https://www.cmsimpleforum.com/) für Hinweise,
Anregungen und das Testen.

Und zu guter letzt vielen Dank an [Peter Harteg](https://www.harteg.dk/),
den „Vater“ von CMSimple, und allen Entwicklern von [CMSimple_XH](https://www.cmsimple-xh.org/de/)
ohne die es dieses phantastische CMS nicht gäbe.
