<?php

declare(strict_types=1);

namespace App\CommissionTask\Model;

class UserType
{
    const LEGAL = 'legal';
    const NATURAL = 'natural';

    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function legal(): UserType
    {
        return new self(self::LEGAL);
    }

    public static function natural(): UserType
    {
        return new self(self::NATURAL);
    }
}
