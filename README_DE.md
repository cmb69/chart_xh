# Chart_XH

- [Voraussetzungen](#voraussetzungen)
- [Download](#download)
- [Installation](#installation)
- [Einstellungen](#einstellungen)
- [Verwendung](#verwendung)
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

Um ein Diagramm auf einer Seite anzuzeigen:

    {{{chart}}}

Um ein Diagramm im Template anzuzeigen:

    <?=chart()?>

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
