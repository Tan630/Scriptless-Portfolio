<?php

/**
 * A word of caution: elements "collected" from a file retain their original
 * $ownerDocument. Grafting an element onto a new document does not destroy
 * the original element.
 */


/**
 * Since the PHP DOM extension depends on libxml, and the latter requires a root node,
 *  at many points of writing this library I had to add a "root" wrapper then remove it later.
 */
function mapNodes(?callable $callable, DOMText|DOMNode|DOMNodeList|array $nodes, array ...$arrays): array {
    if ($nodes instanceof DOMNode) {
        return mapNodes($callable, [$nodes], $arrays);
    } elseif ($nodes instanceof DOMNodeList) {
        return mapNodes($callable, iterator_to_array($nodes), $arrays);
    } else {
        return array_map($callable
        , $nodes
        , $arrays);
    }
}

function collectNodes(string $path): ?DOMNodeList {
    $elementHost = new DOMDocument();
    $elementHost->loadHTMLFile($path, LIBXML_NOBLANKS | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR );
    return $elementHost->documentElement->childNodes;
}

function collectDocument(string $path): ?DOMElement {
    $elementHost = new DOMDocument();
    $elementHost->loadHTMLFile($path, LIBXML_NOBLANKS | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR);
    return $elementHost->documentElement;
}

function mapNodesFile(?callable $callable, string $path, array ...$arrays): array {
    return mapNodes($callable, collectNodesFile($file1), $arrays);
}

/**
 * Import all nodes in the provided file to the provided document.
 * The new nodes are appended after the last element inside <html>.
 *
 * @param "DOMDocument" $document Document that receives
 *
 * @param "DOMDocument" $document
 *
 */
function graftNodesToDocument(DOMText|DOMNode|DOMNodeList|array $nodes, DOMDocument $document) {
    mapNodes(
        function (DOMNode $node) use ($document) {
            $node = $document->importNode($node, true);
            if (is_null($document->documentElement)) {
                $document->appendChild($node);
            } else {
                $document->documentElement->appendChild($node);
            }
        }
        , $nodes);
}

// importNodesToDocument($doc1, collectNodesFile($file1));
// importNodesToDocument($doc1, collectNodesFile($file2));

// echo $doc1->saveHTML();

//$doc1->loadHTMLFile($file1, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);



/**
 * Append all nodes in the given file to the given element.
 * For now, the second parameter is overaloaded to accept four types: a string, a node,
 * a nodelist, or an array.
 * 
 */
function graftAfterNode(DOMText|DOMNode|DOMNodeList|array $insertNode, DOMText|DOMElement $afterNode) {
    $iToNode = $afterNode;
    mapNodes(
        function (DOMNode $node) use (&$iToNode) {
            $node = $iToNode->ownerDocument->importNode($node, true);
            insertAfter($node, $iToNode);
            $iToNode = $node;
        }
        , $insertNode);
}

function graftBeforeNode(DOMText|DOMNode|DOMNodeList|array $nodes, DOMText|DOMElement $beforeNode) {
    $iToNode = $beforeNode;
    mapNodes(
        function (DOMNode $node) use ($beforeNode) {
            $node = $beforeNode->ownerDocument->importNode($node, true);
            insertBefore($node, $beforeNode);
        }
        , $nodes);
}



function graftInto(DOMText|DOMNode|DOMNodeList|array $nodes, DOMElement $parent) {
    mapNodes(
        function (DOMNode $node) use ($parent) {
            $node = $parent->ownerDocument->importNode($node, true);
            $parent -> append($node);
        }
        , $nodes);
}

/**
 * Attempt to insert before the first child; append if empty
 */
function graftIntoAsFirstChild(DOMText|DOMNode|DOMNodeList|array $nodes, DOMElement $parent) {
    if (is_null($parent -> childNodes)) {
        graftInto($nodes, $parent);
    } else {
        graftBeforeNode($nodes, $parent->firstChild);
    }
}

function graftIntoAsLastChild(DOMText|DOMNode|DOMNodeList|array $nodes, DOMElement $parent) {
    if (is_null($parent -> childNodes)) {
        graftInto($nodes, $parent);
    } else {
        graftAfterNode($nodes, $parent->lastChild);
    }
}



// // libxml_use_internal_errors( true );
// iterator_to_array

// $file2 = "./resources/second.html";




// // importToDocumentFile($doc1, $file2);

// insertBeforeNodeFile($doc1->documentElement->childNodes[3]->childNodes[1], $file2);



// echo $doc1->saveHTML();
function insertBefore(DOMNode $insert, DOMNode $before) {
    try {
        $before->parentNode->insertBefore($insert, $before);
    } catch (\Exception $e) {
        $before->parentNode->appendChild( $insert );
    }
}

function insertAfter(DOMNode $insert, DOMNode $before) {
    try {
        $before->parentNode->insertBefore($insert, $before->nextSibling);
    } catch (\Exception $e){
        $before->parentNode->appendChild( $insert );
    }
}
