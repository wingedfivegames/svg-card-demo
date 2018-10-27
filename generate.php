<?php

// Load the CSV.
$csv = get_csv();
var_dump($csv);

// Process the CSV.
process_cards($csv);

/**
 * Gets the CSV contents.
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

function _combine_array(&$row, $key, $header) {
  $row = array_combine($header, $row);
}

/**
 * Process the cards.
 */
function process_cards($csv) {
  $svg = 'template-card.svg';
  $content = file_get_contents($svg);
  foreach ($csv as $row) {
    $name = 'card-' . strtolower($row['name']) . '.svg';
    $text = replace_content($content, $row);
    file_put_contents($name, $text);
    export_file($name);
  }
}

/**
 * Data processing for cards.
 */
function replace_content($content, $row) {
  $text = $content;
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
  $text = str_replace($find, $replace, $content);
  return $text;
}

/**
 * Exports a file as PNG.
 */
function export_file($file, $location = 'output', $format = 'png', $dpi = 300) {
  $newfile = str_replace('.svg', '.png', $file);
  shell_exec("inkscape $file --export-png='$location/$newfile' -d 300");
}
