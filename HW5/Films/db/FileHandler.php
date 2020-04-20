<?php

namespace db;
/**
 * Implements important functions regarding files.
 */
class FileHandler
{

    /**
     * Appends a line to the file.
     * @param string $filename
     * @param string $line
     */
    public function add_line(string $filename, string $line){
        file_put_contents($filename, $line . "\n", FILE_APPEND);
    }

    /**
     * Returns all lines of a file.
     * @param string $filename The file name
     * @return array Array of lines from the file.
     */
    public function get_lines(string $filename): array{
        $lines = file($filename);

        $lines = array_map('trim', $lines);
        $lines = array_filter($lines, function($line){
            if ($line !== '' && (substr($line, 0, 1) !== "#")){
                return true;
            }

            return false;
        });

        return $lines;
    }

    public function get_lines_contain(string $filename, $search_str): array{
        $lines = [];

        $all_lines = file($filename);

        foreach($all_lines as $line){
            if (strpos($line, $search_str) !== false){
                $lines[] = $line;
            }
        }
        return $lines;
    }

    public function get_line_starting_with(string $filename, $start_string): string{
        $line_to_return = '';
        $handle = fopen($filename, "r");
        if ($handle){
            while (($line = fgets($handle)) !== false){
                if (substr($line, 0, strlen($start_string)) === $start_string){
                    $line_to_return = $line;
                    break;
                }
            }
            fclose($handle);
        }
        return $line_to_return;
    }


    /**
     * Deletes a line from the file.
     * @param string $filename The file that needs a line removed
     * @param string $delete_line The line to be removed.
     */
    public function delete_line(string $filename, string $delete_line){
        $file_contents = file_get_contents($filename);
        $file_contents = str_replace($delete_line, '', $file_contents);
        file_put_contents($filename, $file_contents);
    }

    public function get_last_line(string $filename){
        $data = file($filename);
        if (count($data) > 0){
            return $data[count($data)-1];
        }else{
            return '';
        }

    }
}