<?php
session_start();

// Initialize settings if not already set
if (!isset($_SESSION['settings'])) {
    $_SESSION['settings'] = [
        'level' => 1,
        'operator' => 'addition',
        'custom_range' => [1, 10],
        'num_items' => 10,
        'max_diff' => 5,
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['settings']['level'] = isset($_POST['level']) ? (int)$_POST['level'] : 1;
    $_SESSION['settings']['operator'] = isset($_POST['operator']) ? $_POST['operator'] : 'addition';
    $_SESSION['settings']['custom_range'] = [
        isset($_POST['range_start']) ? (int)$_POST['range_start'] : 1,
        isset($_POST['range_end']) ? (int)$_POST['range_end'] : 10,
    ];
    $_SESSION['settings']['num_items'] = isset($_POST['num_items']) ? (int)$_POST['num_items'] : 10;
    $_SESSION['settings']['max_diff'] = isset($_POST['max_diff']) ? (int)$_POST['max_diff'] : 5;

    // Redirect back to avoid re-submitting the form
    header("Location: settings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Settings</title>
    <style>
        :root {
            --background: #f9f9f9;
            --text-color: #333;
            --button-bg: #007bff;
            --button-hover: #0056b3;
            --card-bg: #fff;
            --border-color: #ddd;
        }

        [data-theme="dark"] {
            --background: #333;
            --text-color: #f9f9f9;
            --button-bg: #61dafb;
            --button-hover: #21a1f1;
            --card-bg: #444;
            --border-color: #555;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--background);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            max-width: 400px;
            width: 100%;
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        label, input, select {
            display: block;
            margin: 10px 0;
            font-size: 1em;
        }

        input[type="number"] {
            width: calc(50% - 10px);
            padding: 5px;
            margin-right: 10px;
        }

        button, a {
            display: inline-block;
            width: calc(50% - 10px);
            text-align: center;
            background-color: var(--button-bg);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            margin: 10px 5px;
        }

        button:hover, a:hover {
            background-color: var(--button-hover);
        }

        .theme-toggle {
            margin: 10px auto;
            text-align: center;
            cursor: pointer;
            font-size: 0.9em;
            color: var(--button-bg);
        }

        .custom-range {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quiz Settings</h1>
        <form method="POST">
            <label for="level">Select Difficulty Level:</label>
            <select name="level" id="level" onchange="toggleCustomRange()">
                <option value="1" <?php echo ($_SESSION['settings']['level'] == 1) ? 'selected' : ''; ?>>Easy</option>
                <option value="2" <?php echo ($_SESSION['settings']['level'] == 2) ? 'selected' : ''; ?>>Medium</option>
                <option value="3" <?php echo ($_SESSION['settings']['level'] == 3) ? 'selected' : ''; ?>>Custom</option>
            </select>

            <div class="custom-range" id="customRangeFields">
                <label>Custom Range:</label>
                <input type="number" name="range_start" value="<?php echo $_SESSION['settings']['custom_range'][0]; ?>" placeholder="Start">
                <input type="number" name="range_end" value="<?php echo $_SESSION['settings']['custom_range'][1]; ?>" placeholder="End">
            </div>

            <label for="operator">Choose Operator:</label>
            <select name="operator" id="operator">
                <option value="addition" <?php echo ($_SESSION['settings']['operator'] == 'addition') ? 'selected' : ''; ?>>Addition</option>
                <option value="subtraction" <?php echo ($_SESSION['settings']['operator'] == 'subtraction') ? 'selected' : ''; ?>>Subtraction</option>
                <option value="multiplication" <?php echo ($_SESSION['settings']['operator'] == 'multiplication') ? 'selected' : ''; ?>>Multiplication</option>
                <option value="division" <?php echo ($_SESSION['settings']['operator'] == 'division') ? 'selected' : ''; ?>>Division</option>
            </select>

            <label for="num_items">Number of Questions:</label>
            <input type="number" name="num_items" value="<?php echo $_SESSION['settings']['num_items']; ?>">

            <label for="max_diff">Max Difference for Fake Answers:</label>
            <input type="number" name="max_diff" value="<?php echo $_SESSION['settings']['max_diff']; ?>">

            <button type="submit">Save Settings</button>
            <a href="homepage.php">Start to Quiz</a>
        </form>
    </div>

    <script>
        const toggleCustomRange = () => {
            const level = document.getElementById("level").value;
            const customRangeFields = document.getElementById("customRangeFields");
            customRangeFields.style.display = level === "3" ? "block" : "none";
        };


        window.onload = toggleCustomRange;
    </script>
</body>
</html>
