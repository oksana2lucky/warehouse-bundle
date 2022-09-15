<?php
namespace Oksana2lucky\WarehouseBundle\Import\Reader;
/**
 * CSVReader
 * Class to parse and iterate CSV files on the fly
 *
 * <code>
 *      $myCsv = new CSVReader("$path/csv.csv");
 *
 *      foreach ($myCsv as $data) {
 *          echo $data[2] ."\n";
 *      }
 * </code>
 *
 * @author      Mardix
 */

class CSVReader implements \Iterator, ReaderInterface
{

    private $delimiter;

    private $rowDelimiter;

    private $fileHandle = null;

    private $position = 0;

    private $data = array();

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->fileHandle) {
            fclose($this->fileHandle);
            $this->fileHandle = null;
        }
    }

    public function init(string $filename, string $delimiter = ",", string $rowDelimiter = "r")
    {
        $this->delimiter = $delimiter;

        $this->rowDelimiter = $rowDelimiter;

        $this->position = 0;

        $this->fileHandle = fopen($filename, $this->rowDelimiter);

        if ($this->fileHandle === FALSE) {
            throw new \Exception("Unable to open file: {$filename}");
        } else {
            $this->parseLine();
        }

    }

    /**
     * Rewind iterator to the first element
     */
    public function rewind()
    {
        if ($this->fileHandle) {
            $this->position = 0;
            rewind($this->fileHandle);
        }
        $this->parseLine();
    }

    /**
     * Return the current row
     *
     * @return Array
     */
    public function current()
    {
        return $this->data;
    }

    /**
     * Return the key of the current row
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Move forward to the next element
     */
    public function next()
    {
        $this->position++;
        $this->parseLine();
    }

    /**
     * Check if current position is valid
     *
     * @return bool
     */
    public function valid()
    {
        return $this->data !== [];
    }

    /**
     * Parse each line to convert it to array
     *
     * @return void
     */
    public function parseLine()
    {
        $this->data = array();

        if (!feof($this->fileHandle)) {
            $line = trim(utf8_encode(fgets($this->fileHandle)));
            $this->data = str_getcsv($line, $this->delimiter);
        }
    }
}