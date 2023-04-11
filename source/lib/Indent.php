<?php
namespace OCSSDOM\Tools{
    /**
     * The `Indent` class contains tools that indent
     * a string. Its methods operate on reading braces.
     * @global
     */
    class Indent {
        public static $tabSequence = "  ";
        public static function indentString (string $str): string {
            $lines = explode("\n", $str);

            $tabLevel = 0;

            $result = "";

            foreach ($lines as $line){ 
                
                $tabLevelIncrease = substr_count($line, '{');
                $tabLevelDecrease = substr_count($line, '}');
                $tabLevelChange = $tabLevelIncrease - $tabLevelDecrease;
                $tabLevel += $tabLevelChange;
                $tabs = "";
                if ($tabLevelChange > 0) {
                    $tabs = str_repeat("  ", $tabLevel - $tabLevelChange);
                } else {
                    $tabs = str_repeat("  ", $tabLevel);
                }

                

                $result .=  $tabs
                         . $line
                         . "\n";
                
            }
            return $result;
        }

        /**
         * Change the sequence characters that will be inserted at the beginning of each intended line,
         * repeated to the current indentaiotn level.
         */
        public function setTabSequence(string $sequence):void {
            static::$tabSequence = $sequence;
        }
    }
}