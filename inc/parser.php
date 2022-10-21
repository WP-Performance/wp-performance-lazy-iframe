<?php

namespace WPPerformance\LazyIframe\inc\parser;

/** find image with classes nolazy for replace lazy to eager */
function parse($string)
{
    $document = new \DOMDocument();
    // hide error syntax warning
    libxml_use_internal_errors(true);

    $document->loadHTML(mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8'));
    $xpath = new \DOMXpath($document);

    parseIframe($xpath);

    return $document->saveHTML();
}


/**
 * parse iframe element
 */
function parseIframe(\DOMXpath $xpath): void
{
    // search iframe
    $iframes = $xpath->query("//iframe");
    foreach ($iframes as $key => $iframe) {
        // switch src to data-src
        $src = $iframe->getAttribute('src');
        $iframe->setAttribute('data-src', $src);
        $iframe->removeAttribute('src');
        // add class b-lazy
        $class = $iframe->getAttribute('class');
        $iframe->setAttribute('class', $class . ' b-lazy');
    }
}
