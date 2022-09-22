<?php

namespace Oksana2lucky\WarehouseBundle\Import\Reader;

class CSVReader implements \Iterator, ReaderInterface
{
    /**
     * @var string
     */
    private string $delimiter;

    /**
     * @var string
     */
    private string $rowDelimiter;

    /**
     * @var mixed|null
     */
    private mixed $fileHandle = null;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var array
     */
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

    /**
     * @param string $filename
     * @param string $delimiter
     * @param string $rowDelimiter
     * @return void
     * @throws \Exception
     */
    public function init(string $filename, string $delimiter = ",", string $rowDelimiter = "r"): void
    {
        $this->delimiter = $delimiter;

        $this->rowDelimiter = $rowDelimiter;

        $this->position = 0;

        $this->fileHandle = fopen($filename, $this->rowDelimiter);

        if ($this->fileHandle === false) {
            throw new \Exception("Unable to open file: {$filename}");
        } else {
            $this->parseLine();
        }
    }

    /**
     * Rewind iterator to the first element
     */
    public function rewind(): void
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
     * @return array
     */
    public function current(): array
    {
        return $this->data;
    }

    /**
     * Return the key of the current row
     *
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * Move forward to the next element
     */
    public function next(): void
    {
        $this->position++;
        $this->parseLine();
    }

    /**
     * Check if current position is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->data !== [];
    }

    /**
     * Parse each line to convert it to array
     *
     * @return void
     */
    public function parseLine(): void
    {
        $this->data = array();

        if (!feof($this->fileHandle)) {
            $line = trim(utf8_encode(fgets($this->fileHandle)));
            $this->data = str_getcsv($line, $this->delimiter);
        }
    }
}
