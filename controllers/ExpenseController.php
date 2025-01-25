<?php

namespace Controllers;

require_once "./models/Expense.php";
require_once "./constants.php";

use DateTime;
use Models\Expense;

class ExpenseController
{
    public function store(string $description, int $amount)
    {
        // Create the expense
        $expense = Expense::create(description: $description, amount: $amount);

        // Load all expenses
        if (file_exists(DB_FILE_PATH)) {
            $expenses = $this->loadExpenses(true);
        } else {
            $expenses = [];
        }

        // Append the new expense
        $expenses[] = (array) $expense;

        // Save expenses
        $this->saveExpenses($expenses);

        echo "Expense added successfully (ID: " . $expense->id . ")";
        exit;
    }

    public function delete(int $id)
    {
        $expenses = $this->loadExpenses(true);
        $filteredExpenses = array_filter($expenses, fn($t) => $t['id'] !== $id);

        if (count($expenses) === count($filteredExpenses)) {
            echo "Expense with ID: $id not found.";
            exit;
        }

        $this->saveExpenses(
            array_values($filteredExpenses)
        );
        echo "Expense deleted successfully.";
        exit;
    }

    public function list()
    {
        $expenses = $this->loadExpenses(true) ?? [];

        $this->formatOutput($expenses);
        exit;
    }

    public function summary(?int $month)
    {
        $total = 0;
        $expenses = $this->loadExpenses(true);

        // Get the current year
        $currentYear = (int)date('Y');

        // Apply filter if provided
        if ($month) {
            $filteredExpenses = array_filter($expenses, function ($expense) use ($month, $currentYear) {
                $expenseDate = new DateTime($expense['createdAt']);
                $expenseMonth = (int)$expenseDate->format('n');
                $expenseYear = (int)$expenseDate->format('Y');
                return $expenseMonth === $month && $expenseYear === $currentYear;
            });
        } else {
            $filteredExpenses = $expenses;
        }

        // Calculate total expenses
        foreach ($filteredExpenses as $e) {
            $total += $e['amount'];
        }

        // Print the total summary
        if ($month) {
            echo "Total expenses for month $month: $$total" . PHP_EOL;
        } else {
            echo "Total expenses: $$total" . PHP_EOL;
        }
        exit;
    }

    private function formatOutput(array $expenses)
    {
        // Define column headers
        $headers = ['ID', 'Date', 'Description', 'Amount'];
        $columnWidths = [
            'ID' => 5,
            'Date' => 12,
            'Description' => 20,
            'Amount' => 10
        ];

        // Print headers with proper spacing
        foreach ($headers as $header) {
            echo str_pad($header, $columnWidths[$header], ' ', STR_PAD_RIGHT);
        }
        echo N;

        // Print a separator line
        echo str_repeat('-', array_sum($columnWidths) + count($headers) - 1) . N;

        // Print each expense in a formatted way
        foreach ($expenses as $expense) {
            echo str_pad($expense['id'], $columnWidths['ID'], ' ', STR_PAD_RIGHT);
            echo str_pad($expense['createdAt'], $columnWidths['Date'], ' ', STR_PAD_RIGHT);
            echo str_pad($expense['description'], $columnWidths['Description'], ' ', STR_PAD_RIGHT);
            echo str_pad('$' . $expense['amount'], $columnWidths['Amount'], ' ', STR_PAD_RIGHT);
            echo N;
        }
    }

    private function loadExpenses(bool $assoc = false): array
    {
        if (file_exists(DB_FILE_PATH)) {
            $expensesRawFile = file_get_contents(DB_FILE_PATH);
        } else {
            echo "No Expenses exist!", N;
            exit;
        }

        $expenses = json_decode($expensesRawFile, $assoc);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Failed to decode expenses.json: " . json_last_error_msg();
            exit;
        }

        return $expenses;
    }

    private function saveExpenses(array $expenses): void
    {
        $expenses = json_encode($expenses, JSON_PRETTY_PRINT);

        if ($expenses === false) {
            echo "Failed to encode expenses to JSON: " . json_last_error_msg();
            exit;
        }

        $res = file_put_contents(DB_FILE_PATH, $expenses);

        if ($res === false) {
            echo "Failed to write expenses to expenses.json.";
            exit;
        }
    }
}
