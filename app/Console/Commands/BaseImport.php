<?php
namespace Pimeo\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use League\Csv\Reader;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Output\BufferedOutput;

class BaseImport extends Command
{
    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The last outputted line.
     *
     * @var string
     */
    protected $lastLine;

    /**
     * Contain CSV header column
     *
     * @var String[]
     */
    protected $header = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * The last line length.
     *
     * @var int
     */
    protected $lineLength = 0;

    public function __construct()
    {
        parent::__construct();
        $this->output = $this->getOutput();
    }

    /**
     * Read the content of the file.
     *
     * @param  string $content
     * @return array
     * @throws Exception
     */
    public function readFile($content)
    {
        try {
            $csv = Reader::createFromString($content);
            $csv->setDelimiter("\t");
            $csv->setOffset(1);

            $rows = $csv->fetchAssoc($this->header);
            $data = [];

            foreach ($rows as $row) {
                $data[] = $row;
            }
        } catch (Exception $e) {
            $this->failed($e->getMessage());

            throw $e;
        }

        return $data;
    }

    /**
     * Open the file.
     *
     * @param  string $path
     * @return string
     * @throws FileNotFoundException
     */
    protected function openFile($path)
    {
        try {
            $file = $this->files->get($path);
        } catch (FileNotFoundException $e) {
            $this->failed($e->getMessage());

            throw $e;
        }

        return $file;
    }

    /**
     * Append an error message to the last outputted line.
     *
     * @param  string $message
     * @return void
     */
    protected function failed($message)
    {
        $this->lineUp();

        $line = $this->lastLine . ': <error>' . $message . '</error>';

        $this->line(str_pad($line, $this->lineLength + 20, ' ', STR_PAD_RIGHT));
        $this->lineLength = max(strlen($line), $this->lineLength);
    }

    /**
     * Move one line up in the console.
     *
     * @return void
     */
    protected function lineUp()
    {
        $this->getOutput()->write("\033[1A");
    }

    /**
     * Move one line up and clear it.
     *
     * @return void
     */
    protected function clearLineUp()
    {
        $this->lineUp();
        $this->line(str_pad('', $this->lineLength + 20, ' ', STR_PAD_RIGHT));
    }

    /**
     * Save the line to output and write it to the console.
     *
     * @param  string $line
     * @return void
     */
    protected function write($line)
    {
        $this->lastLine = $line;
        $this->lineLength = max(strlen($line), $this->lineLength);

        $this->line($line . '...');
    }

    protected function detail($text)
    {
        $this->lineUp();

        $line = $this->lastLine . ': <info>' . $text . '</info>';

        $this->line(str_pad($line, $this->lineLength + 20, ' ', STR_PAD_RIGHT));
        $this->lineLength = max(strlen($line), $this->lineLength);
    }

    /**
     * Append "done" to the last line.
     *
     * @param string $message
     * @return void
     */
    protected function done($message = 'done')
    {
        $this->lineUp();

        $line = $this->lastLine . ': <comment>' . $message . '</comment>';

        $this->line(str_pad($line, $this->lineLength + 20, ' ', STR_PAD_RIGHT));
        $this->lineLength = max(strlen($line), $this->lineLength);
    }

    /**
     * @param Integer $max
     * @return String[]
     */
    protected function setHeaderByCsvRowNumber($max)
    {
        $header = [];
        for ($i = 0; $i < $max; $i++) {
            array_push($header, (string)$i);
        }

        return $header;
    }

    public function getOutput()
    {
        $output = parent::getOutput();
        if ($output == null) {
            return new BufferedOutput();
        }
        return $output;
    }
}
