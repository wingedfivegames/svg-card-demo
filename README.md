@WingedFiveGames SVG generator
===

This repository demonstrates how to process a CSV into multiple SVG files from a template. If you have [https://inkscape.org/](Inkscape) installed, it will also export the new SVG files to PNG.

The export is included in the repository. To test for yourself, download the files in the root directory:

- generate.php
- list.csv
- template-card.svg

Place them in a folder and then create the 'svg' and 'png' folders.

Then run `php generate.php` to populate the cards.

After you've confirmed that it works for you, feel free to edit the base CSV file and provide your own SVG template. The only part of the script that should require updating is this one:

```
/**
 * Data processing for cards.
 *
 * @param $svg
 *   The content defined by an SVG file.
 * @param $row
 *   A data row from the CSV file.
 *
 * @return
 *   The text for an SVG file.
 */
function replace_svg($svg, $row) {
  $find = [
    'Placeholder Text',
    'Action Text',
    '#ffff00',
  ];
  $replace = [
    $row['name'],
    $row['text'],
    $row['color'],
  ];
  $text = str_replace($find, $replace, $svg);
  return $text;
}
```

The `find` and `replace` elements are simply test strings. Update those to match the content of your CSV and SVG template.
