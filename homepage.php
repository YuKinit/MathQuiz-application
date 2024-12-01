<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Quiz - Home</title>
    <style>
        :root {
            --background: #f9f9f9;
            --text-color: #333;
            --button-bg: #007bff;
            --button-hover: #0056b3;
            --card-bg: #fff;
            --border-color: #ddd;
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

        .buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }

        button, a {
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
        }

        button:hover, a:hover {
            background-color: var(--button-hover);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Math Quiz!</h1>
        
        <div class="buttons">
            <a href="index.php">Start Quiz</a>
            <a href="settings.php">Settings</a>
        </div>
    </div>
</body>
</html>