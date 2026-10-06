<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Export;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class ArrayToCsv
{
    protected $delimiter;
    protected $text_separator;
    protected $replace_text_separator;
    protected $line_delimiter;

    public function __construct($delimiter = ';', $text_separator = '"', $replace_text_separator = "'", $line_delimiter = "\n")
    {
        $this->delimiter = $delimiter;
        $this->text_separator = $text_separator;
        $this->replace_text_separator = $replace_text_separator;
        $this->line_delimiter = $line_delimiter;
    }

    public function convert($input)
    {
        $lines = [];
        foreach ($input as $v) {
            $lines[] = $this->convertLine($v);
        }

        return implode($this->line_delimiter, $lines);
    }

    private function convertLine($line)
    {
        $csv_line = [];
        foreach ($line as $v) {
            $csv_line[] = is_array($v) ?
                $this->convertLine($v) :
                $this->text_separator.str_replace($this->text_separator, $this->replace_text_separator, $v).$this->text_separator;
        }

        return implode($this->delimiter, $csv_line);
    }
}
