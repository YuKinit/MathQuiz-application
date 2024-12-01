<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['level'])) {
        $_SESSION['settings']['level'] = intval($_POST['level']);
    }
    if (isset($_POST['operator'])) {
        $_SESSION['settings']['operator'] = $_POST['operator'];
    }
    if (isset($_POST['custom_range'])) {
        $range = explode('-', $_POST['custom_range']);
        $_SESSION['settings']['custom_range'] = [intval($range[0]), intval($range[1])];
    }
    if (isset($_POST['num_items'])) {
        $_SESSION['settings']['num_items'] = intval($_POST['num_items']);
    }
    if (isset($_POST['max_diff'])) {
        $_SESSION['settings']['max_diff'] = intval($_POST['max_diff']);
    }
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
</head>
<body>
    <h1>Settings</h1>
    <form method="POST">
        <label>Level:</label><br>
        <input type="radio" name="level" value="1" <?php echo $_SESSION['settings']['level'] == 1 ? 'checked' : ''; ?>> Level 1 (1-10)<br>
        <input type="radio" name="level" value="2" <?php echo $_SESSION['settings']['level'] == 2 ? 'checked' : ''; ?>> Level 2 (11-100)<br>
        <input type="radio" name="level" value="3" <?php echo $_SESSION['settings']['level'] == 3 ? 'checked' : ''; ?>> Custom Level: 
        <input type="text" name="custom_range" placeholder="1-50" value="<?php echo implode('-', $_SESSION['settings']['custom_range']); ?>"><br><br>

        <label>Operator:</label><br>
        <input type="radio" name="operator" value="addition" <?php echo $_SESSION['settings']['operator'] == 'addition' ? 'checked' : ''; ?>> Addition<br>
        <input type="radio" name="operator" value="subtraction" <?php echo $_SESSION['settings']['operator'] == 'subtraction' ? 'checked' : ''; ?>> Subtraction<br>
        <input type="radio" name="operator" value="multiplication" <?php echo $_SESSION['settings']['operator'] == 'multiplication' ? 'checked' : ''; ?>> Multiplication<br>
        <input type="radio" name="operator" value="division" <?php echo $_SESSION['settings']['operator'] == 'division' ? 'checked' : ''; ?>> Division<br><br>

        <label>Number of Items:</label>
        <input type="number" name="num_items" value="<?php echo $_SESSION['settings']['num_items']; ?>"><br><br>

        <label>Max Difference of Choices:</label>
        <input type="number" name="max_diff" value="<?php echo $_SESSION['settings']['max_diff']; ?>"><br><br>

        <button type="submit">Save Settings</button>
    </form>
    <a href="index.php">Back to Quiz</a>
</body>
</html>
