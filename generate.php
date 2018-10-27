<?php

/**
 * Playing card generation script.
 *
 * This file should be run from the command line. It assumes that your script
 * shares a directory with two files, list.csv and template-card.svg.
 *
 * > php generate.php
 */

// Load the CSV.
$csv = get_csv();

// Process the CSV.
process_cards($csv, 'svg', 'png', TRUE);

/**
 * Gets the CSV contents.
 *
 * @return array
 *   An array of content rows, keyed by the header row of the CSV.
 */
function get_csv() {
  $filename = "list.csv";
  $csv = array_map('str_getcsv', file($filename));
  $head = array_shift($csv);
  foreach ($head as $item) {
    $header[] = strtolower($item);
  }
  array_walk($csv, '_combine_array', $header);
  return $csv;
}

/**
 * Combines header and content arrays using array_walk().
 */
function _combine_array(&$row, $key, $header) {
  $row = array_combine($header, $row);
}

/**
 * Processes the cards and exports the files to disk.
 *
 * @param array $csv
 *   The contents of a CSV file, as an array, with a header row.
 * @param $location
 *   The directory to export the file to, relative to the script. Defaults to
 *   'svg'.
 * @param boolean $inkscape_export
 *   Indicates that the script should copy the SVG to PNG using inkscape.
 *   If you do not have inkscape installed, set this to false above.
 */
function process_cards($csv, $location = 'svg', $target = 'png', $inkscape_export = TRUE) {
  $file = 'template-card.svg';
  $svg = file_get_contents($file);
  foreach ($csv as $row) {
    $name = 'card-' . strtolower($row['name']) . '.svg';
    $text = replace_svg($svg, $row);
    file_put_contents($location . '/' . $name, $text);
    if ($inkscape_export) {
      export_file($name, $location, $target);
    }
  }
}

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

/**
 * Exports an SVG file as PNG.
 *
 * @param $file
 *   The name of the file being exported.
 * @param $location
 *   The directory of the file to be exported, relative to the script. Defaults
 *   to 'svg'.
 * @param $target
 *   The directory to export the file to, relative to the script. Defaults to
 *   'png'.
 * @param $format
 *   The output format. Defaults to PNG.
 * @param int $dpi
 *   Dots per inch of the export file. Defaults to 300.
 */
function export_file($file, $location = 'svg', $target = 'png', $format = 'png', $dpi = 300) {
  $newfile = str_replace('.svg', '.png', $file);
  shell_exec("inkscape '$location/$file' --export-png='$target/$newfile' -d 300");
}
