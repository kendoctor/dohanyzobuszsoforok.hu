<?php

require_once('clsMsDocGenerator.php');

$doc = new clsMsDocGenerator();
$text = $_POST['text'];
$text = iconv('utf-8', 'iso-8859-2', $text);
$text = str_replace("\r\n", '<br>', $text);
$doc->addParagraph($text);
$doc->documentCharset = 'iso-8859-2';
$doc->output('dohanyzo-buszsofor.doc');

?>
