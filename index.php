<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
use Sunra\PhpSimple\HtmlDomParser;

if ( ! isset($_GET['wiki'])) {
    die('no wiki page specified');
}

$wiki_location = $_GET['wiki'];

if ( ! filter_var($wiki_location, FILTER_VALIDATE_URL)){
    die('invalid wiki page specified');
}

// Load HTML
$html = HtmlDomParser::file_get_html($wiki_location);

// Get title
$title = $html->find('h1',0)->find('span',0)->innertext;

// Remove table of contents etc
$html->find('#siteSub', 0)->innertext = '';
$html->find('#jump-to-nav', 0)->innertext = '';
$html->find('#toc', 0)->innertext = '';

// Remove edit, cite links etc.
$i = 0;
while ($html->find('.mw-editsection', $i)) {
    $html->find('.mw-editsection', $i)->innertext = '';
    $i++;
}
$i = 0;
while ($html->find('.reference', $i)) {
    $html->find('.reference', $i)->innertext = '';
    $i++;
}
$i = 0;
while ($html->find('.Template-Fact', $i)) {
    $html->find('.Template-Fact', $i)->innertext = '';
    $i++;
}
$i = 0;
while ($html->find('.magnify', $i)) {
    $html->find('.magnify', $i)->innertext = '';
    $i++;
}

// Remove category links
$html->find('#catlinks', 0)->innertext = '';

// Remove some footer links
$i = 0;
while ($html->find('.navbox', $i)) {
    $html->find('.navbox', $i)->innertext = '';
    $i++;
}
$i = 0;
while ($html->find('.metabox', $i)) {
    $html->find('.navbox', $i)->innertext = '';
    $i++;
}

// Print webpage
echo "<!DOCTYPE html><html><head><meta charset=\"UTF-8\" /><title>$title</title></head><body>";
echo "<h1>$title</h1>";
echo $html->find('div#mw-content-text', 0);
echo "</body></html>";


