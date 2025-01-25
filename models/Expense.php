<?php

namespace Models;

class Expense
{
    private static $_path = __DIR__ . '/..' . '/last_id.txt';
    private static $_id = 0;
    public $id;
    public $description;
    public $amount;
    public $createdAt;

    public function __construct(
        string $description,
        string $amount,
        string $createdAt,
    ) {
        self::loadLastId();
        $this->id = ++self::$_id;
        $this->description = $description;
        $this->amount = $amount;
        $this->createdAt = $createdAt;
        self::saveLastId();
    }

    private static function loadLastId()
    {
        if (file_exists(self::$_path)) {
            self::$_id = (int) file_get_contents(self::$_path);
        }
    }

    private static function saveLastId()
    {
        file_put_contents(self::$_path, self::$_id);
    }

    public static function create(string $description, float $amount): Expense
    {
        $now = date('Y-m-d');

        $task = new Expense(
            $description,
            $amount,
            $now
        );

        return $task;
    }
}
