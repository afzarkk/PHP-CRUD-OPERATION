<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Data</title>

    <link rel="stylesheet" href="view_data.css">

    <script type="text/javascript">
    function confirmDelete() {
        return confirm("Are you sure you want to delete this record?");
    }
    </script>

</head>

<body>
    <header>
        <h1>View Data</h1>
    </header>
    <main>
        <table style="width: 100%">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Language</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'conn.php';

                // Pagination setup
                $limit = 5;
                if (isset($_GET["page"])) {
                    $page = $_GET["page"];
                } else {
                    $page = 1;
                };
                $start_from = ($page - 1) * $limit;


                $sql_count = "SELECT COUNT(*) FROM `data`";
                $result_count = mysqli_query($conn, $sql_count);
                $total_records = mysqli_fetch_array($result_count)[0];
                $total_pages = ceil($total_records / $limit);

                //current page
                $sql = "SELECT * FROM `data`   ORDER BY id DESC  LIMIT $start_from, $limit";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $i = $start_from + 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $date = new DateTime($row['Dob']);
                        $formattedDate = $date->format('d-m-Y');
                ?>

                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row['Name'] ?></td>
                    <td><?php echo $formattedDate ?></td>
                    <td><?php echo $row['Age'] ?></td>
                    <td><?php echo $row['Gender'] ?></td>
                    <td><?php echo $row['Language'] ?></td>
                    <td><?php echo $row['Phone_no'] ?></td>
                    <td><?php echo $row['Address'] ?></td>
                    <td><?php echo $row['City'] ?></td>
                    <td><?php echo "<img src='" . $row['Image'] . "' height='100' width='100' />" ?></td>

                    <td>
                        <a class="button" href="update.php?update=<?php echo $row['id'] ?>">Edit</a>
                        <a class="button" href="delete.php?delete=<?php echo $row['id'] ?>"
                            onclick="return confirmDelete()">Delete</a>
                    </td>
                </tr>

                <?php
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='10'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>


        <div class="pagination">
            <?php //pagination link
            if ($page > 1) {
                echo "<a href='view_data.php?page=" . ($page - 1) . "'>Previous</a>";
            }
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo "<a class='active' href='view_data.php?page=" . $i . "'>" . $i . "</a>";
                } else {
                    echo "<a href='view_data.php?page=" . $i . "'>" . $i . "</a>";
                }
            }
            if ($page < $total_pages) {
                   echo "<a href='view_data.php?page=" . ($page + 1) . "'>Next</a>";
            }
            ?>
        </div>
        <!-- <a href="insert.php">Insert Data</a> -->
    </main>
</body>

</html>