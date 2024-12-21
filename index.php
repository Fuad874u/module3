<?php
// Path to the tasks file
$tasksFile = 'tasks.json';

// Load tasks from the tasks.json file
function loadTasks() {
    global $tasksFile;
    return file_exists($tasksFile) ? json_decode(file_get_contents($tasksFile), true) : [];
}

// Save tasks to tasks.json
function saveTasks($tasks) {
    global $tasksFile;
    file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
}

// Handle add task
if (isset($_POST['task'])) {
    $taskText = trim($_POST['task']);
    if (!empty($taskText)) {
        $tasks = loadTasks();
        $tasks[] = ['task' => htmlspecialchars($taskText), 'done' => false];
        saveTasks($tasks);
        header('Location: index.php');
        exit();
    }
}

// Handle mark as done/undone
if (isset($_GET['mark'])) {
    $taskIndex = $_GET['mark'];
    $tasks = loadTasks();
    if (isset($tasks[$taskIndex])) {
        $tasks[$taskIndex]['done'] = !$tasks[$taskIndex]['done'];
        saveTasks($tasks);
    }
    header('Location: index.php');
    exit();
}

// Handle delete task
if (isset($_GET['delete'])) {
    $taskIndex = $_GET['delete'];
    $tasks = loadTasks();
    if (isset($tasks[$taskIndex])) {
        array_splice($tasks, $taskIndex, 1);
        saveTasks($tasks);
    }
    header('Location: index.php');
    exit();
}

$tasks = loadTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>To-Do List</h1>
        <form method="POST">
            <input type="text" name="task" placeholder="Enter a new task" required>
            <button type="submit">Add Task</button>
        </form>

        <ul>
            <?php foreach ($tasks as $index => $task): ?>
                <li>
                    <span class="<?= $task['done'] ? 'done' : '' ?>" onclick="window.location.href='?mark=<?= $index ?>'"><?= $task['task'] ?></span>
                    <button onclick="window.location.href='?delete=<?= $index ?>'">Delete</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>