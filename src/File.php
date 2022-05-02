<?php

namespace Laravel\Nova\LogViewer;

use Symfony\Component\Process\Process;

class File
{
    /**
     * The path to the log file.
     *
     * @var string
     */
    public $file;

    /**
     * Create a new File instance.
     *
     * @param  string  $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Return the number of lines for the given file.
     *
     * @return int
     */
    public function numberOfLines()
    {
        return (int) $this->executeCommand('awk', 'END {print NR}', $this->file);
    }

    /**
     * Return the content after the given line number.
     *
     * @param  int  $lineNumber
     * @return string
     */
    public function contentAfterLine($lineNumber)
    {
        return $this->executeCommand('awk', "NR > {$lineNumber}", $this->file);
    }

    /**
     * Execute the given command and arguments in a process.
     *
     * @param  string  $command
     * @param  array  ...$args
     * @return string
     */
    public function executeCommand($command, ...$args)
    {
        $process = new Process(array_merge([$command], $args));
        $process->run();

        return $process->getOutput();
    }
}
