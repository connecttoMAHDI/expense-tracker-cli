# Expense-Tracker CLI

Sample solution for [expense-tracker-cli](https://roadmap.sh/projects/expense-tracker) challenge from [roadmap.sh](https://roadmap.sh).

This is a command-line-based expense tracker application that allows you to add, delete, list, summarize, and export expenses. The application stores data in JSON format and supports exporting to a CSV file.

---

## Features
- **Add Expense:** Add a new expense with description, amount, and optional category.
- **Delete Expense:** Delete an expense by its ID.
- **List Expenses:** List all expenses, optionally filtered by category.
- **Summary:** Display total expenses, optionally filtered by month and/or category.
- **Export to CSV:** Export expenses to a CSV file, optionally filtered by category and/or month.

---

## How to Run

### 1. Clone the Repository
```bash
git clone https://github.com/connecttoMAHDI/expense-tracker-cli.git
```

### 2. Navigate to the Project Directory
```bash
cd expense-tracker-cli
```

### 3. Check Available Commands
Run the following command to see a list of supported commands:
```bash
php expense-tracker.php --help
```

You will see the following output:
```
Here is the list of commands:
- add --description [description] --amount [amount] --category [?category]
- delete --id [id]
- list --category [?category]
- summary
- summary --month [month] --category [?category]
- export --category [?category] --month [?month]
```

---

## Commands

### Add Expense
Add a new expense with a description, amount, and optional category.
```bash
php expense-tracker.php add --description "Groceries" --amount 50 --category "food"
```

### Delete Expense
Delete an expense by its ID.
```bash
php expense-tracker.php delete --id 1
```

### List Expenses
List all expenses, optionally filtered by category.
```bash
php expense-tracker.php list
php expense-tracker.php list --category "food"
```

### Summary
Display a summary of expenses:
- Total expenses for all entries:
  ```bash
  php expense-tracker.php summary
  ```
- Total expenses for a specific month and/or category:
  ```bash
  php expense-tracker.php summary --month 1 --category "food"
  ```

### Export to CSV
Export expenses to a CSV file, optionally filtered by category and/or month.
```bash
php expense-tracker.php export
php expense-tracker.php export --category "food" --month 1
```
This will create a file named `expenses_export.csv` in the root directory of the project.

---

## Example
### Adding an Expense
```bash
php expense-tracker.php add --description "Coffee" --amount 5 --category "beverages"
```
Output:
```
Expense added successfully (ID: 4)
```

### Listing Expenses
```bash
php expense-tracker.php list
```
Output:
```
ID   Date         Description         Amount     Category       
---------------------------------------------------------------
1    2025-01-28   Bread              $5         food           
2    2025-01-28   Icecream           $10        supermarket    
3    2025-01-28   Pasta              $30        supermarket    
```

### Exporting to CSV
```bash
php expense-tracker.php export --category "food" --month 1
```
Output:
```
Expenses exported successfully to /path/to/expenses_export.csv
```