# Chart_XH

- [Requirements](#requirements)
- [Download](#download)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Settings](#settings)
- [Usage](#usage)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Credits](#credits)

## Requirements

Chart_XH is a plugin for [CMSimple_XH](https://cmsimple-xh.org/).
It requires CMSimple_XH ≥ 1.7.0, and PHP ≥ 8.0.0 with the DOM extension.
Chart_XH also requires [Plib_XH](https://github.com/cmb69/plib_xh) ≥ 1.10;
if that is not already installed (see *Settings*→*Info*),
get the [lastest release](https://github.com/cmb69/plib_xh/releases/latest),
and install it.

## Download

The [lastest release](https://github.com/cmb69/chart_xh/releases/latest)
is available for download on Github.

## Installation

The installation is done as with many other CMSimple_XH plugins.

1.  Backup the data on your server.
1.  Unzip the distribution on your computer.
1.  Upload the whole folder `chart/` to your server into
    the `plugins/` folder of CMSimple_XH.
1.  Set write permissions to the subfolders `config/`, `css/`, and
    `languages/`.
1.  Check under `Plugins` → `Chart` in the back-end of the website,
    if all requirements are fulfilled.

## Quick Start

There is a sample chart `voting.xml` in `chart/examples/`.  Copy the file into
the contents folder of CMSimple_XH (`content/chart/`).  Then embed the sample
chart on a page:

    {{{chart('voting')}}}

Switch to view mode, and you should see a bar chart showing the results of the
German federal elections in three categories for the years 2017, 2021, and 2025.
Now go to the plugin administration (`Plugins` → `Chart` → `Administration`),
select `voting` and press `Edit`.  You see a form where you can edit the chart.
For now, we only want to change the grouping, so that the votes for each party
are shown right besides each other.  To that purpose, check `transposed`, and
`Save`.  Go back to the page where the chart is shown, and you'll notice the
difference.  Now we want to show the chart as a semi-doughnut chart instead of
a bar chart.  Go to the edit form, and select `Semi-Doughnut` from the `Type`
drop-down. Save and view the chart.  Looks a bit strange, because we have
three years; so go back to the edit form, and remove the labels of the more
recent elections (the labels contents should only be `2017`).  Save and view the
chart again.  Looks more like we are used to.

Now go back to the edit form, and play around a bit.  Maybe choose a
different kind of chart type, or change the colors of the parties (there should
be color pickers; otherwise consider to use a contemporary browser).  Maybe change
the order of the datasets (there are arrow buttons on the right), or delete a couple
of datasets (there are trash can buttons), or maybe add a new dataset (there is
a plus button at the top of the dataset table).  And you may want to edit the
values; you can do this directly in the textarea, but you can also use the edit
button (pencil) where you get numeric input fields (makes it easier if you are
accustomed to a decimal separator other that a dot).  If you want to add or remove
values, you need to do that in the textarea directly.  Also you can try different
aspect ratios (the width of the chart is always 100%; the aspect ratio determines
the height).

Finally, you may want to create your own chart.  Go back to the administration
(`Plugins` → `Chart` → `Administration`), press `New`, and edit the chart like
before.  The only difference is that you now have to fill in the `Name` field,
which is disabled when you are editing an existing chart.  Afterwards, you can
show the new chart instead of the `voting` sample chart by changing the plugin
call accordingly.

## Settings

The configuration of the plugin is done as with many other
CMSimple_XH plugins in the back-end of the Website. Select
`Plugins` → `Chart`.

You can change the default settings of Chart_XH under
`Config`. Hints for the options will be displayed when hovering
over the help icons with your mouse.

Localization is done under `Language`. You can translate the
character strings to your own language if there is no appropriate
language file available, or customize them according to your
needs.

The look of Chart_XH can be customized under `Stylesheet`.

## Usage

To display a chart on a page:

    {{{chart}}}

To display a chart in the template:

    <?=chart()?>

## Troubleshooting

Report bugs and ask for support either on
[Github](https://github.com/cmb69/chart_xh/issues)
or in the [CMSimple_XH Forum](https://cmsimpleforum.com/).

## License

Chart_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Chart_XH is distributed in the hope that it will be useful,
but *without any warranty*; without even the implied warranty of
*merchantibility* or *fitness for a particular purpose*. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Chart_XH.  If not, see <https://www.gnu.org/licenses/>.

Copyright © Christoph M. Becker

## Credits

Chart_XH was inspired by *ChoseX*.

The plugin is powered by [Chart.js](https://www.chartjs.org/).
Many thanks for providing this awesome tool under the MIT license!

The plugin icon is designed by [Vectors Market - Flaticon](https://www.flaticon.com/free-icons/graph).
Many thanks for making this icon available for free.

Many thanks to the community at the
[CMSimple_XH Forum](https://www.cmsimpleforum.com/) for tips, suggestions
and testing.

And last but not least many thanks to [Peter Harteg](httsp://www.harteg.dk),
the “father” of CMSimple,
and all developers of [CMSimple_XH](https://www.cmsimple-xh.org)
without whom this amazing CMS would not exist.
