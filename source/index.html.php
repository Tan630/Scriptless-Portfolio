<?php

include_once ('./source/odom.php');
include_once ('./source/tabextract.php');

$fragHTML = './source/resources/parts/document.html';
$fragHead = './source/resources/parts/head.html';
$fragBody = './source/resources/parts/body.html';
$fragHeader = './source/resources/parts/header.html';
$fragMain = './source/resources/parts/main.html';
$fragButtonWrapper = './source/resources/parts/button-wrapper.html';

$fragNavbar = './source/resources/parts/navbar.html';
$fragButton = './source/resources/parts/button.html';
$fragTabContent = './source/resources/parts/tab.html';

$fragTabs = './source/resources/tabs/';

$fragFooter = './source/resources/parts/footer.html';
$tabsDir = "./source/resources/tabs";



// $body = "./resources/first.html";
// $file2 = "./resources/second.html";

// $root = new DOMDocument();
// importNodesToDocument($root, collectDocument($file1));

// echo $root->saveHTML();


$root = new DOMDocument();
$root->formatOutput = true;

echo (buildDocument($root)->saveHTML());

function buildDocument(DOMDocument $doc): DOMDocument {
    global $fragHTML;
    $doc->loadHTMLFile($fragHTML, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    buildHead($doc);
    buildBody($doc);
    return $doc;
}

/**
 * Add <head> as the first element of <html>
 */
function buildHead(DOMDocument $doc): void {
    global $fragHead;
    $headNode = collectNodes($fragHead);
    graftInto($headNode, $doc->documentElement);
}

/**
 * Add <body> as the last element of <html>
 */
function buildBody(DOMDocument $doc): void {
    global $fragBody;

    $body = collectNodes($fragBody);
    
    
    graftInto($body, $doc->documentElement);

    buildHeader($doc);
    buildMains($doc);
    buildFooter($doc);
}

/**
 * Add <header> as the first element of <body>
 * Preconditon: the argument has a <body> which the header can be grafted into
 */
function buildHeader(DOMDocument $doc): void {
    global $fragHeader;
    $headerNode = collectNodes($fragHeader);
    
    $bodyAnchor = $doc->getElementByID('body-anchor');
    // echo get_class();
    graftIntoAsFirstChild($headerNode, $bodyAnchor);

    /** Recalled the problem - cannot find "body" despite searching for the text.
     * More specifically, searching for "tag" "body" returns an empty DOMText
    */
}

/**
 * Add multiple <main> elements
 * Precondition: an element with id='tabend' exists in the document.
 *      new tabs are prepended to that element.
 *      This condition is fulfilled "artificially" by presuming the
 *          existence of a <header> and adding that element after it.
 */
function buildMains(DOMDocument $doc): void {
    global $fragMain;
    global $tabsDir;
    // var_export(extractMainFiles($tabsDir));
    // $tabMain = collectNodes(fragMain);

    $header = $doc->getElementsByTagName('header')->item(0);
    $tabMarker = $doc->createElement('div');
    $tabMarker->setAttribute('id', 'tabsend');
    graftAfterNode($tabMarker, $header);

    $tabs = extractTabFiles($tabsDir);
    
    foreach (array_reverse($tabs) as $key => $val) {
        graftBeforeNode(makeTab($key, $tabs), $doc->getElementByID('tabsend'));
    }


}

function makeTab(string $tabname, array $tabs): DOMElement {
    global $fragMain;
    global $fragNavbar;
    global $fragTab;
    global $tabsDir;

    global $fragMain;
    global $fragNavbar;
    global $fragTab;
    global $tabsDir;
    global $fragButtonWrapper;
    

    $tabContent = $tabs[$tabname];

    $main = collectNodes($fragMain)->item(0);
    $main -> setAttribute('id', makeTabId($tabname));
    
    $nav = collectNodes($fragNavbar)->item(0);

    $buttonWrapper = collectNodes($fragButtonWrapper)->item(0);

    
    

    foreach ($tabs as $ikey => $ival) {
        graftIntoAsLastChild(makeButton($ikey, $ikey == $tabname), $buttonWrapper);
        
    }
    graftIntoAsLastChild($buttonWrapper, $nav);
    graftInto($nav, $main);
    

    
    graftIntoAsLastChild(makeTabContent($tabContent), $main);

    
    


    return $main;
}



function makeButton(string $tabName, bool $buttonIsOpen = false): DOMElement {
    global $fragButton;
    

    $button = collectNodes($fragButton)->item(0);
    $button-> setAttribute('href', makeTabLinkName($tabName));
    if ($buttonIsOpen){$button-> setAttribute('class', $button-> getAttribute('class') . ' tab-bar__item--open');}
    $button -> nodeValue = $tabName;
    
    return $button;
}

function makeTabContent(string $tabContent): DOMElement {

    global $fragTabContent;

    $mainContent = collectNodes($fragTabContent)->item(0);
    
    //$mainContent -> appendChild($mainContent->ownerDocument->createTextNode($tabContent));

    $fragment = $mainContent->ownerDocument->createDocumentFragment();
    try {
    @$fragment->appendXML($tabContent);
    
} catch (\Exception $e) {/*do nothing*/}
    // {TODO} [The solution is temporary - the following line of code ]
    
    @$mainContent->appendChild($fragment);
    
    


    // $mainContent -> textContent = $tabContent;

    return $mainContent;
}


function makeTabLinkName(string $tabName): string {
    return '#tab' . $tabName;
}

function makeTabId(string $tabName): string {
    return 'tab' . $tabName;
}






// <main id="tab1" class="tab f-container" style="width: 100%">
//     <nav class="tab-bar f-row" aria-label="Top Navigation Tabs, Expanded">
//       <div class="ico-container">
//         <div class="ico tab-bar__ico clickable">  </div>
//       </div>
//         <a class="tab-bar__item clickable tab-bar__item--open" href="#tab1"> Tab 1 </a>
//         <a class="tab-bar__item clickable" href="#tab2"> Tab 2 </a>
//         <a class="tab-bar__item clickable"> Portfolio </a>
//         <a class="tab-bar__item clickable"> Contact </a>

//     </nav>
//     <div class="tab-content content-box">

//     </div>
//   </main>





/**
 * Add <footer> as the last element of <body>
 * Preconditon: the argument has a <body> which the footer can be grafted into
 */
function buildFooter(DOMDocument $doc): void {
    global $fragFooter;
    $footerNode = collectNodes($fragFooter);
    $bodyAnchor = $doc->getElementByID('body-anchor');

    //$body = $doc->getElementsByTagName('body')->item(0);
    // echo get_class();
    graftIntoAsLastChild($footerNode, $bodyAnchor);
}






// if ($tabs['orderfile']) {

    
// }

