<?php

declare(strict_types=1);

namespace App\CommissionTask\Model;

class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var UserType
     */
    private $type;

    public function __construct(int $id, UserType $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): UserType
    {
        return $this->type;
    }
}
