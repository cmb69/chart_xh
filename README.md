# Chart_XH

- [Requirements](#requirements)
- [Download](#download)
- [Installation](#installation)
- [Settings](#settings)
- [Usage](#usage)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Credits](#credits)

## Requirements

Chart_XH is a plugin for [CMSimple_XH](https://cmsimple-xh.org/).
It requires CMSimple_XH ≥ 1.7.0, and PHP ≥ 8.0.0.
Chart_XH also requires [Plib_XH](https://github.com/cmb69/plib_xh) ≥ 1.9;
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
