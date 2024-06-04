<?php
include 'connect.php';
session_start();
$username = '';

if (isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
  $username = $_SESSION["username"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["send"])) {
    $tname = $_POST['tname'];
    $name = $_POST['name'];
    $username = isset($_SESSION["username"]) ? $_SESSION["username"] : '';
    $recipient_email = $_POST['recipient_email'];
    $message = $_POST['message'];
    $currentDate = date("Y-m-d"); 

    if (filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
       
        $fromEmail = $recipient_email;
        echo "Data inserted successfully";
        
        $sql = "INSERT INTO tform(tname, name, username, recipient_email, message, currentDate) 
                VALUES('$tname', '$name', '$username', '$recipient_email', '$message', '$currentDate')";
        
        $result = mysqli_query($con, $sql);
        
        if (!$result) {
            die(mysqli_error($con));
        }
    } else {
        
        echo "Invalid email address";
    }
}

// Fetch user data including role
$query = "SELECT id, username, name, role FROM user1";
$result = mysqli_query($con, $query);
$userOptions = "";

if ($result) {
    while ($row = $result->fetch_assoc()) {
      $userOptions .= "<option value='{$row['name']}-{$row['role']}'>{$row['name']} - {$row['role']}</option>";
    }
} else {
    echo "Query failed: " . mysqli_error($con);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Assignment Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            text-align: center;
            color: #333333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555555;
        }
        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #dddddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            color: #333333;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .small-text {
            font-size: 12px;
            color: gray;
            text-align: right;
            margin-top: -16px;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function updateWordCount() {
            const descriptionField = document.getElementById("taskDescription");
            const wordCount = descriptionField.value.length;
            const wordCountDisplay = document.getElementById("wordCountDisplay");
            wordCountDisplay.textContent = `${wordCount}/500 characters`;
        }

        function setCurrentDate() {
            const dateField = document.getElementById("dateOfAssignment");
            const today = new Date();
            const formattedDate = today.toLocaleDateString('en-GB');
            dateField.value = formattedDate;
        }

        window.onload = setCurrentDate;
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Task Assignment Form</h2>
        <form method="post" action="process_form.php">
            <label for="taskName">Task Name:</label>
            <input type="text" id="taskName" name="taskName" placeholder="Enter task name">

            <label for="personName">Person Name:</label>
            <input type="text" id="personName" name="personName" placeholder="Enter person name">

            <label for="taskDescription">Task Description:</label>
            <textarea id="taskDescription" name="taskDescription" rows="4" cols="50" oninput="updateWordCount()" placeholder="Enter task description"></textarea>
            <div class="small-text">
                <span id="wordCountDisplay">0/500 characters</span>
            </div>

            <label for="remarks">Add Remarks:</label>
            <input type="text" id="remarks" name="remarks" placeholder="Enter remarks">

            <label for="dateOfAssignment">Date of Assignment:</label>
            <input type="text" id="dateOfAssignment" name="dateOfAssignment" readonly>

            <label for="dateOfSubmission">Date of Submission:</label>
            <input type="date" id="dateOfSubmission" name="dateOfSubmission">

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
