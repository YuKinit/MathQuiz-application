<?php
session_start();

// Initialize score tracking
if (!isset($_SESSION['score']) || !is_array($_SESSION['score'])) {
    $_SESSION['score'] = ['correct' => 0, 'wrong' => 0];
} else {
    if (!isset($_SESSION['score']['correct'])) {
        $_SESSION['score']['correct'] = 0;
    }
    if (!isset($_SESSION['score']['wrong'])) {
        $_SESSION['score']['wrong'] = 0;
    }
}


if (!isset($_SESSION['score']) || isset($_POST['start_quiz'])) {
    $_SESSION['score'] = ['correct' => 0, 'wrong' => 0];
}

// Initialize settings if not set
if (!isset($_SESSION['settings'])) {
    $_SESSION['settings'] = [
        'level' => 1,
        'operator' => 'addition',
        'custom_range' => [1, 10],
        'num_items' => 5,
        'max_diff' => 10,
    ];
}

// Initialize current question and remaining items
if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = null;
}
if (!isset($_SESSION['remaining_items'])) {
    $_SESSION['remaining_items'] = $_SESSION['settings']['num_items'];
}

// Generate a new question
function generateQuestion($operator, $range) {
    $num1 = rand($range[0], $range[1]);
    $num2 = rand($range[0], $range[1]);

    switch ($operator) {
        case 'addition':
            return [$num1, $num2, $num1 + $num2, '+'];
        case 'subtraction':
            return [$num1, $num2, $num1 - $num2, '-'];
        case 'multiplication':
            return [$num1, $num2, $num1 * $num2, '*'];
        case 'division':
            $num1 *= $num2; // Ensure divisibility
            return [$num1, $num2, $num1 / $num2, '/'];
        default:
            return [$num1, $num2, $num1 + $num2, '+'];
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['answer'])) {
        $message = "You need to choose an answer!";
    }   elseif (isset($_POST['answer'])) {
        $correctAnswer = $_SESSION['current_question'][2];
        $userAnswer = intval($_POST['answer']);

        if ($userAnswer === $correctAnswer) {
            $_SESSION['score']['correct']++;
            $message = "Correct!";
        } else {
            $_SESSION['score']['wrong']++;
            $message = "Wrong! The correct answer was $correctAnswer.";
        }

        $_SESSION['remaining_items']--;
        if ($_SESSION['remaining_items'] <= 0) {
            $message .= " Quiz finished!";
            $_SESSION['remaining_items'] = $_SESSION['settings']['num_items'];
        }
    } elseif (isset($_POST['start_quiz'])) {
        $_SESSION['score'] = ['correct' => 0, 'wrong' => 0];
        $_SESSION['remaining_items'] = $_SESSION['settings']['num_items'];
        $message = "Quiz started!";
    } elseif (isset($_POST['close'])) {
        session_destroy();
        header("Location: settings.php");
        exit();
    }
}

// Generate a new question if needed
if ($_SESSION['remaining_items'] > 0) {
    $range = $_SESSION['settings']['level'] == 1
        ? [1, 10]
        : ($_SESSION['settings']['level'] == 2
            ? [11, 100]
            : $_SESSION['settings']['custom_range']);
    $operator = $_SESSION['settings']['operator'];

    $_SESSION['current_question'] = generateQuestion($operator, $range);
}

[$num1, $num2, $correctAnswer, $operator] = $_SESSION['current_question'];
$options = [$correctAnswer];
while (count($options) < 4) {
    $diff = rand(1, $_SESSION['settings']['max_diff']);
    $fakeAnswer = $correctAnswer + ($diff * (rand(0, 1) ? 1 : -1));
    if (!in_array($fakeAnswer, $options) && $fakeAnswer >= 0) {
        $options[] = $fakeAnswer;
    }
}
shuffle($options);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Quiz</title>
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

        p {
            text-align: center;
            font-size: 1.1em;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin: 10px 0;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }

        button, a.button {
            flex: 1; 
            text-align: center;
            background-color: var(--button-bg);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            min-width: 100px;
        }

        button:hover, a.button:hover {
            background-color: var(--button-hover);
        }

        .theme-toggle {
            margin: 10px auto;
            text-align: center;
            cursor: pointer;
            font-size: 0.9em;
            color: var(--button-bg);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Math Quiz</h1>
        <p>Score: Correct: <?php echo $_SESSION['score']['correct']; ?> | Wrong: <?php echo $_SESSION['score']['wrong']; ?></p>
        <?php if (isset($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <?php if ($_SESSION['remaining_items'] > 0) : ?>
                <p><strong>What is <?php echo "$num1 $operator $num2 = ?"; ?></strong></p>
                <?php foreach ($options as $index => $option) : ?>
                    <label>
                        <input type="radio" name="answer" value="<?php echo $option; ?>"> <?php echo $option; ?>
                    </label>
                <?php endforeach; ?>
                <button type="submit">Submit</button>
            <?php else : ?>
                <p>Quiz completed! Restart to play again.</p>
            <?php endif; ?>
        </form>

        <div class="buttons">
            <form method="POST" style="flex: 1;">
                <button name="start_quiz" style="width: 100%;">Restart Quiz</button>
            </form>
            <a href="index.php" class="button" style="flex: 1;">Close</a>
            <a href="settings.php" class="button" style="flex: 1;">Settings</a>
        </div>

    </div>

</body>
</html>

