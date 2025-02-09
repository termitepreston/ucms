<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Default Page Title' ?></title>
    <style>
        table {
            width: 100%;
            /* Make table responsive within its container */
            border-collapse: collapse;
            /* Single border for the whole table */
            font-family: sans-serif;
            /* Simple, clean font */
            margin-bottom: 20px;
            /* Space below the table */
        }

        th,
        td {
            padding: 8px 12px;
            /* Comfortable padding within cells */
            text-align: left;
            /* Align text to the left in cells */
            /* Optional: subtle horizontal rule (border-bottom) for row separation */
            border-bottom: 1px solid #eee;
            /* Very light grey border */
        }

        /* Style for table header cells (<th>) - optional */
        th {
            background-color: #f8f8f8;
            /* Light grey background for header */
            font-weight: bold;
            /* Bold header text */
        }

        /* Optional: Hover effect on table rows (for interactivity) */
        tbody tr:hover {
            background-color: #f0f0f0;
            /* Slightly darker grey on hover */
        }

        /* Optional: Remove bottom border from the last row for a cleaner look */
        tbody tr:last-child th,
        tbody tr:last-child td {
            border-bottom: none;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 0.2em;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        button[type="submit"]:disabled {
            background-color: #cccccc;
            color: #666666;
            cursor: not-allowed;
        }

        body {
            min-height: 100vh;
        }

        form {
            max-width: 33%;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 0.2em;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 0.2em;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        body {
            font-family: sans-serif;
        }

        .form-container {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: calc(100% - 22px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group .password-input-group {
            position: relative;
        }

        .form-group .password-input-group input[type="password"],
        .form-group .password-input-group input[type="text"] {
            width: calc(100% - 52px);
        }

        /* Adjust width for button */
        .form-group .password-input-group button {
            position: absolute;
            right: 1px;
            top: 1px;
            bottom: 1px;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-left: none;
            border-radius: 0 4px 4px 0;
            background-color: #f9f9f9;
            cursor: pointer;
        }

        .form-group .password-input-group button:hover {
            background-color: #eee;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            display: none;
        }

        .form-actions button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .form-actions button:hover {
            background-color: #0056b3;
        }

        .form-actions button:disabled {
            background-color: #ccc;
            cursor: default;
        }

        .form-actions button:disabled:hover {
            background-color: #ccc;
        }

        .valid-input {
            border-color: green;
        }

        .invalid-input {
            border-color: red;
        }

        .valid-input+.error-message,
        .invalid-input+.error-message.valid {
            display: none !important;
            /* Hide valid messages if input is valid */
        }

        .invalid-input+.error-message.invalid {
            display: block !important;
            /* Show invalid messages if input is invalid */
        }
    </style>
</head>

<body>