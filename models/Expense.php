<?php

namespace Models;

class Expense
{
    private static $_path = __DIR__ . '/..' . '/expense_last_id.txt';
    private static $_id = 0;
    public $id;
    public $category;
    public $description;
    public $amount;
    public $createdAt;

    public function __construct(
        ?string $category,
        string $description,
        string $amount,
        string $createdAt,
    ) {
        self::loadLastId();
        $this->id = ++self::$_id;
        $this->category = $category;
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

    public static function create(?string $category, string $description, float $amount): Expense
    {
        $now = date('Y-m-d');

        $expense = new Expense(
            $category,
            $description,
            $amount,
            $now
        );

        return $expense;
    }
}
