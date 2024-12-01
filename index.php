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
    if (isset($_POST['answer'])) {
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
    <title>Simple Mathematics</title>
</head>
<body>
    <h1>Simple Mathematics</h1>

    <p>Score: Correct: <?php echo $_SESSION['score']['correct']; ?> | Wrong: <?php echo $_SESSION['score']['wrong']; ?></p>
    <?php if (isset($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <?php if ($_SESSION['remaining_items'] > 0) : ?>
            <p>What is <?php echo "$num1 $operator $num2 = ?"; ?></p>
            <?php foreach ($options as $index => $option) : ?>
                <label>
                    <input type="radio" name="answer" value="<?php echo $option; ?>"> <?php echo $option; ?>
                </label><br>
            <?php endforeach; ?>
            <button type="submit">Submit</button>
        <?php else : ?>
            <p>Quiz completed! Restart to play again.</p>
        <?php endif; ?>
    </form>

    <form method="POST">
        <button name="start_quiz">Start Quiz</button>
        <button name="close">Close</button>
        <a href="settings.php">Settings >></a>
    </form>
</body>
</html>
