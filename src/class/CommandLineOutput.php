<?php

namespace Blocks\Console;

/**
 * A class for printing text to the command line.
 */
class CommandLineOutput {
    public static function error( $text ) {
        echo "\033[01;31m{$text}\033[0m".PHP_EOL;
    }

    public static function success( $text ) {
        echo "\033[00;32m{$text}\033[0m".PHP_EOL;
    }

    public static function title( $text ) {
        $text_length = mb_strlen( $text );

        $terminal_width = self::getTerminalWidth();

        $equal_signs = str_repeat( '=', $terminal_width - $text_length - 5 );

        echo "=== {$text} {$equal_signs}".PHP_EOL;
    }

    private static function getTerminalWidth() {
        // Check if we're in a proper terminal environment
        if (!isset($_ENV['TERM']) && !isset($_SERVER['TERM'])) {
            return 80; // Default width when no terminal is available
        }

        // Try different methods to get terminal width
        $terminal_width = 0;
        
        // Method 1: tput cols (most reliable when TERM is set)
        $terminal_width = intval( shell_exec( 'tput cols 2>/dev/null' ) );
        
        // Method 2: stty size as fallback
        if ($terminal_width <= 0) {
            $stty_output = shell_exec('stty size 2>/dev/null');
            if ($stty_output) {
                $dimensions = explode(' ', trim($stty_output));
                if (isset($dimensions[1])) {
                    $terminal_width = intval($dimensions[1]);
                }
            }
        }
        
        // Method 3: environment variable as another fallback
        if ($terminal_width <= 0 && isset($_ENV['COLUMNS'])) {
            $terminal_width = intval($_ENV['COLUMNS']);
        }

        // If all methods fail, use default width (80 characters)
        return ($terminal_width > 0) ? $terminal_width : 80;
    }
}
