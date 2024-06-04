<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Page</title>
    <link rel="stylesheet" href="Home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Borel&family=Caveat:wght@700&family=Dancing+Script:wght@700&family=Handjet&family=Kanit:wght@400;500;800&family=Lilita+One&family=Luxurious+Roman&family=Montserrat:wght@700&family=Mukta:wght@400;500&family=Pacifico&family=Poppins&family=Roboto+Slab:wght@500&family=Shadows+Into+Light&family=Signika:wght@600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        /* General styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto Slab', serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #d0e1f9;
            height: 70px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 40px;
        }

        header img {
            height: 60px;
        }

        nav {
            display: flex;
            align-items: center;
        }

        nav div {
            display: flex;
            align-items: center;
        }

        nav div#task-container {
            background-color: #f06a6a;
            border-radius: 15px;
            padding: 5px 10px;
            margin-right: 10px;
        }

        nav div#task-container p {
            margin: 0;
        }

        nav div#task-container a {
            text-decoration: none;
            color: black;
        }

        nav div#task-container a:hover {
            color: whitesmoke;
        }

        nav div.search {
            background-color: whitesmoke;
            border-radius: 20px;
            padding: 7px 10px;
        }

        nav div.search input[type="text"] {
            border: none;
            outline: none;
            background: transparent;
            margin-left: 5px;
        }

        .title-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            position: relative;
        }

        .title-container h2 {
            margin: 0;
            text-align: center;
            flex-grow: 1;
        }

        #sort-button {
            position: absolute;
            right: 0;
            font-size: 0.8rem;
            padding: 5px 10px;
            background-color: #f06a6a;
            border-radius: 15px;
            transition: background 0.3s ease;
        }

        #sort-button:hover {
            background-color: #d35454;
        }

        table {
            width: 95%; /* Increased width for better fit */
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 15px rgba(64, 64, 64, 0.7);
            background: white;
        }

        th, td {
            padding: 15px; /* Increased padding for better appearance */
            text-align: center;
        }

        th {
            background-color: #3B9EBF;
            color: white;
            text-transform: uppercase;
            font-size: 1rem; /* Increased font size for better readability */
        }

        td {
            font-size: 0.9rem;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:nth-child(odd) {
            background-color: #e9e9e9;
        }

        tr:hover {
            background-color: #ddd;
        }

        #pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        #pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #3B9EBF;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        #pagination a.active,
        #pagination a:hover {
            background-color: #2576A7;
        }
    </style>
</head>
<body>
    <header>
        <img src="https://adoreearth.org/assets/images/ADORE.png" alt="" id="background">
        <nav>
            <div id="task-container">
                <i class="ri-add-circle-line"></i>
                <p><a href="task.php">Create Task</a></p>
            </div>
            <div class="search" id="search-bar">
                <i class="ri-search-2-line"></i>
                <input type="text" placeholder="Search" id="s-bar" onkeyup="searchTable()">
            </div>
        </nav>
    </header>

    <?php
    include 'connect.php'; // Assuming this file contains your database connection

    $query = "SELECT * FROM tform";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }
    ?>

    <div class="title-container">
        <h2>Task Records</h2>
        <div id="sort-button">
            <p><a href="#" onclick="sortTable()">Sort by Message</a></p>
        </div>
    </div>

    <table id="taskTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Recipient Email</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['username']}</td>";
                echo "<td>{$row['recipient_email']}</td>";
                echo "<td>{$row['message']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <div id="pagination" class="pagination-container"></div>

    <script>
        let currentPage = 1;
        const rowsPerPage = 10;
        const totalPages = 10;

        function displayTablePage(page) {
            const table = document.getElementById("taskTable");
            const tr = table.getElementsByTagName("tr");
            const totalRows = tr.length - 1; // Exclude the header row
            currentPage = page;

            // Hide all rows
            for (let i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
            }

            // Display the relevant rows for the current page only if on page 1
            if (page === 1) {
                const start = (page - 1) * rowsPerPage + 1;
                const end = Math.min(start + rowsPerPage - 1, totalRows + 1);
                for (let i = start; i < end; i++) {
                    tr[i].style.display = "";
                }
            }

            updatePaginationControls(totalPages);
        }

        function updatePaginationControls(totalPages) {
            const pagination = document.getElementById("pagination");
            pagination.innerHTML = "";

            for (let i = 1; i <= totalPages; i++) {
                const pageLink = document.createElement("a");
                pageLink.textContent = i;
                pageLink.href = "#";
                pageLink.className = i === currentPage ? "active" : "";
                pageLink.addEventListener("click", function (e) {
                    e.preventDefault();
                    displayTablePage(i);
                });
                pagination.appendChild(pageLink);
            }
        }

        function searchTable() {
            const input = document.getElementById("s-bar");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("taskTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let tdArray = tr[i].getElementsByTagName("td");
                let found = false;

                for (let j = 0; j < tdArray.length; j++) {
                    if (tdArray[j]) {
                        if (tdArray[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }

                if (found) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        function sortTable() {
            const table = document.getElementById("taskTable");
            let switching = true;
            let shouldSwitch;
            let i;
            let x, y;

            while (switching) {
                switching = false;
                const rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[3];
                    y = rows[i + 1].getElementsByTagName("TD")[3];

                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            displayTablePage(currentPage);
        });
    </script>
</body>
</html>
