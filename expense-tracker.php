<?php

require_once "./constants.php";
require_once "./enums/Commands.php";
require_once "./controllers/ExpenseController.php";

use Enums\Commands;
use Controllers\ExpenseController;

$command = $argv[1] ?? null;
$args = parseArguments(array_slice($argv, 2));
$expenseController = new ExpenseController();

function parseArguments($argv)
{
    $args = [];
    foreach ($argv as $key => $arg) {
        if (str_starts_with($arg, '--')) {
            $argName = ltrim($arg, '--');
            $args[$argName] = $argv[$key + 1] ?? null; // Get the next value as the argument's value
        }
    }
    return $args;
}
function help()
{
    echo N;
    echo "Here is the list of commands:", N;
    echo "- add --description [description] --amount [amount] --category [?category]", N;
    echo "- delete --id [id]", N;
    echo "- list --category [?category]", N;
    echo "- summary", N;
    echo "- summary --month [month] --category [?category]", N, N;
    return;
}

switch ($command) {
    case Commands::ADD:
        $category = $args['category'] ?? null;
        $description = $args['description'] ?? null;
        $amount = $args['amount'] ?? null;
        if ($description && $amount) {
            // Validate amount
            if ($amount < 0) {
                echo "[amount] cann't be negative.";
                exit;
            }
            $expenseController->store($category, $description, $amount);
        } else {
            echo "[description] and [amount] are required.";
            exit;
        }
        break;
    case Commands::DELETE:
        $id = $args['id'] ?? null;
        if ($id) {
            $expenseController->delete($id);
        } else {
            echo "[id] is required.";
            exit;
        }
        break;
    case Commands::LIST:
        $category = $args['category'] ?? null;
        $expenseController->list(category: strtolower($category));
        break;
    case Commands::SUMMARY:
        $category = $args['category'] ?? null;
        $month = $args['month'] ?? null;
        $expenseController->summary(
            month: $month,
            category: strtolower($category)
        );
        break;
    case '--help':
        help();
    default:
        echo "Usage: php expense-tracker.cli {command} --arg value --arg value";
}
