<?php

declare(strict_types=1);

namespace App\CommissionTask\Cli;

use App\CommissionTask\Exception\Cli\ArgumentNotPassedException;
use App\CommissionTask\Exception\File\FileNotFoundException;

class ArgumentValidator
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * Key - argument name
     * Value - argument index.
     *
     * @var array
     */
    private $argumentsMapping = [
        'path' => 1,
    ];

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Validate input and returns validated arguments.
     *
     * @throws ArgumentNotPassedException
     * @throws FileNotFoundException
     */
    public function validate()
    {
        $this->validatePathRequired();
        $this->validatePathExists();

        return $this->buildArguments();
    }

    /**
     * @throws ArgumentNotPassedException
     */
    private function validatePathRequired()
    {
        if (!\array_key_exists($this->argumentsMapping['path'], $this->arguments)) {
            throw new ArgumentNotPassedException('Path to CSV file');
        }
    }

    /**
     * Check existing file.
     *
     * @throws FileNotFoundException
     */
    private function validatePathExists()
    {
        $pathToFile = $this->arguments[$this->argumentsMapping['path']];
        if (!file_exists($pathToFile)) {
            throw new FileNotFoundException($pathToFile);
        }
    }

    /**
     * Build arguments response.
     */
    private function buildArguments(): array
    {
        $data = [];
        foreach ($this->argumentsMapping as $name => $index) {
            $data[$name] = $this->arguments[$index] ?? null;
        }

        return $data;
    }
}
