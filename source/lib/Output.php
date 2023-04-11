<?php
namespace OCSSDOM\Tools {

    
    // {TODO} There are questions to be had, for example how to "relinquish" control to the next level of 
    // output buffer. As it stands, calling flush on an ob would only invoke its handler; the handler
    // must have some way to speciry that "yes I am done here, you can remove this layer of ob and let the next
    // layer's handler take over now." Find out how to do this. 
    
    /**
     * @stateful
     * @global
     * 
     * The `OutputControl` class acts as a wrapper for the underlying
     * output buffer. 
     */
    class OutputOverride {
        public static function engage(): void {
            fclose(STDOUT);
        }
        /**
         * @effectful
         * @param string $filename
         * @return bool Return `true` if the operation succeeds, otherwise return false.
         *
         * Register a output handler function that prints to `$filepath`,
         * disable the default output handler if one exists.
         */

        public static function start(string $filename = 'php://stdout'): int {
            // $output = fopen('php://output')
            return ob_start(
                function ($param, $flag) use($filename): string {
                    file_put_contents($filename, $param); return null;
                }
            );
        }

    }
}

