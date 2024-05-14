<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "todo_list";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// CRUD operations

// Create
if (isset($_POST['addTask'])) {
    $task_name = $_POST['task'];
    $sql = "INSERT INTO tasks (task_name) VALUES ('$task_name')";
    if ($conn->query($sql) === TRUE) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Read
$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);

$tasks = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

// Update
if (isset($_POST['updateTask'])) {
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    $sql = "UPDATE tasks SET task_name='$task_name' WHERE id=$task_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete
if (isset($_POST['deleteTask'])) {
    $task_id = $_POST['task_id'];
    $sql = "DELETE FROM tasks WHERE id=$task_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        form {
            margin-bottom: 10px;
        }
        input[type="text"] {
            padding: 5px;
            width: 250px;
        }
        button {
            padding: 5px 10px;
            cursor: pointer;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 5px;
            padding: 5px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .edit-field {
            display: none;
        }
    </style>
</head>
<body>
    <h2>Simple To-Do List</h2>
    <form action="" method="post">
        <input type="text" name="task" placeholder="Enter your task">
        <button type="submit" name="addTask">Add Task</button>
    </form>
    <ul>
        <?php
        // Display tasks in reverse order to make new tasks appear at the top
        $tasks = array_reverse($tasks);
        foreach ($tasks as $task) {
            echo "<li>
                    <span class='task'>{$task['task_name']}</span>
                    <input type='text' class='edit-field' value='{$task['task_name']}'>
                    <button class='edit-btn'>Edit</button>
                    <button class='update-btn' style='display:none'>Done</button>
                    <form action='' method='post' style='display:inline;'>
                        <input type='hidden' name='task_id' value='{$task['id']}'>
                        <button type='submit' name='deleteTask'>Delete</button>
                    </form>
                </li>";
        }
        ?>
    </ul>
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const li = this.parentElement;
                const task = li.querySelector('.task');
                const editField = li.querySelector('.edit-field');
                const updateBtn = li.querySelector('.update-btn');
                task.style.display = 'none';
                editField.style.display = 'inline';
                updateBtn.style.display = 'inline';
                editField.focus();
            });
        });

        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', function() {
                const li = this.parentElement;
                const taskId = li.querySelector('[name="task_id"]').value;
                const task = li.querySelector('.task');
                const editField = li.querySelector('.edit-field');
                const updateBtn = li.querySelector('.update-btn');
                task.textContent = editField.value;
                task.style.display = 'inline';
                editField.style.display = 'none';
                updateBtn.style.display = 'none';
            });
        });
    </script>
</body>
</html>
