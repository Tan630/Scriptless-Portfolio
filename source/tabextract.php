<?php






/* The following segment does three things:
    (1) Remove `.' and `..'
    (2) Preserve any file named taborder
    (3) Leave all files that end with html. For each of such files, remove 
*/

function isOrderFile(string $filename): bool {
    return strpos($filename, 'order');
}

function isTabFile(string $filename): bool {
    return str_ends_with($filename, '.html');
}

function extractOrderFile(array &$files): ?string{
    $result = null;
    foreach($files as $key=>$val) {
        if (isOrderFile($val)) {
            $result = $val;
            unset($files[$key]);
        }
    }
    $files = array_values($files);
    return $result;
}

/**
 * Return an array. each key is the tab name, and each value is the file name
 * 'tabs' key are names
 */
function extractTabFiles(string $tabsDir) : array {
    $dirFiles = scandir($tabsDir);
    $orderFileName = extractOrderFile($dirFiles);


    if ($orderFileName) {
        $filecontent = file_get_contents($tabsDir . (str_ends_with($tabsDir, '/')? '' : '/') . $orderFileName);
        $json = json_decode($filecontent, true);
        ksort($json);
        $dirFiles = $json;
    }

    
    $mainFiles = array_map(
        fn($filename) => (
            isTabFile($filename)) ?
                ucfirst(substr($filename, 0, strlen($filename)-5))
            : $filename
        ,
        array_filter(
            $dirFiles
        , fn($filename) => (isTabFile($filename) || isOrderFile($filename))
        )
    );

    $mainContents = array_map(fn($filename)=> file_get_contents($tabsDir . (str_ends_with($tabsDir, '/')? '' : '/') . $filename), $json);
    
    return array_combine($mainFiles, $mainContents);
}





function printNode($var): string {
    return " [-" . $var->ownerDocument->saveHTML($var) . "-] ";
}
