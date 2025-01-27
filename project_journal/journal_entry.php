<?php

include_once('connection.php');
include_once('navbar.php');

if (isset($_REQUEST['save_entry'])) {
    // Get the form data
    $label_id = $_REQUEST['label_id_name'];
    $title = $_REQUEST['title_name'];
    $details = $_REQUEST['details_name'];
    $notes = $_REQUEST['notes_name'];
    $notes2 = $_REQUEST['notes2_name'];
    $time_start = $_REQUEST['time_start_name'];
    $time_end = $_REQUEST['time_end_name'];
    $people_id = $_POST['participants_name'];

    // Get the next available SRNO
    $query_srno = "SELECT MAX(master_id) + 1 AS MAXSRNO FROM journal_master";
    $result_srno = $conn->query($query_srno);
    $row_srno = $result_srno->fetch_assoc();
    $msrno = $row_srno['MAXSRNO'] ?? 1;

    // Prepare the insert query
    $query = "INSERT INTO journal_master (master_id, label_id, title, details, notes, notes2, time_start, time_end) 
              VALUES ($msrno, '$label_id', '$title', '$details', '$notes', '$notes2', '$time_start', '$time_end')";

    foreach ($people_id as $person_id) {
        // Prepare the insert query for journal_participants
        $query_participant = "INSERT INTO journal_participants (journal_id, participant_name) 
                            VALUES ($msrno, '$person_id')";
        
        if ($stmt_participant = $conn->prepare($query_participant)) {
            if ($stmt_participant->execute()) {
                echo "Participant added successfully!<br>";
            } else {
                echo "Error adding participant: " . $stmt_participant->error . "<br>";
            }
            $stmt_participant->close();
        } else {
            echo "Failed to prepare participant insertion: " . $conn->error . "<br>";
        }
    }

    
    if ($stmt = $conn->prepare($query)) {
        // Execute the query
        if ($stmt->execute()) {
            echo "Entry Done !!!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to prepare the query: " . $conn->error;
    }
}

$query1 = "SELECT label_id, label_name FROM journal_labels ORDER BY label_name";
$result1 = $conn->query($query1);

$query2 = "
SELECT 
    CONCAT(p.people_nick, ' ', t.group_name) AS complete_name, p.people_id
FROM 
    people_master p
INNER JOIN 
    people_group t ON p.group_id = t.group_id
ORDER BY p.people_name
";
$result2 = $conn->query($query2);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>

    <div class="container mt-3">
        <font style="font-size: 40px; font-weight: bold;">Create Journal Entry</font><br><br>
        <form method="post">

            <div class="mb-3">
                <label>Label:</label><br>
                <select name="label_id_name" class="form-control">
                    <option value=""></option>
                    <?php while ($row1 = $result1->fetch_assoc()) { ?>
                        <option value="<?php echo $row1['label_id']; ?>">
                            <?php echo $row1['label_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3 mt-3">
                <label>Title:</label>
                <input type="text" class="form-control" name="title_name">
            </div>

            <div class="mb-3 mt-3">
                <label>Title:</label>
                <textarea rows="4" placeholder="Type your message here..." class="form-control" name="details_name"></textarea>
            </div>

            <div class="mb-3 mt-3">
                <label>Notes:</label>
                <input type="text" class="form-control" name="notes_name">
            </div>

            <div class="mb-3 mt-3">
                <label>Notes2:</label>
                <input type="text" class="form-control" name="notes2_name">
            </div>
            <div class="mb-3">
                <label class="form-label">Start Time</label>
                <input type="datetime-local" class="form-control" name="time_start_name">
            </div>
            <div class="mb-3">
                <label class="form-label">End Time</label>
                <input type="datetime-local" class="form-control" name="time_end_name">
            </div>
            <div class="mb-3">
                <label>People:</label><br>
                <div id="languagesContainer">
                    <select name="participants_name[]" class="form-control">
                        <option value=""></option>
                        <?php while ($row2 = $result2->fetch_assoc()) { ?>
                            <option value="<?php echo $row2['people_id']; ?>">
                                <?php echo $row2['complete_name']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <button type="button" class="btn btn-outline-warning mt-2" id="addLanguageBtn">+</button>
            </div>

            <button type="submit" name="save_entry" class="btn btn-primary">Save</button>
        </form>
    </div>

    <br>



    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const languagesContainer = document.getElementById('languagesContainer');
            const addLanguageBtn = document.getElementById('addLanguageBtn');
            let languageCount = 1;

            // PHP will generate this options string
            const optionsHTML = `<?php
                $result2->data_seek(0); // Reset the pointer to the first row
                $options = '';
                while ($row2 = $result2->fetch_assoc()) {
                    $options .= '<option value=\"' . $row2['people_id'] . '\">' . $row2['complete_name'] . '</option>';
                }
                echo $options;
            ?>`;

            // Function to create a new dropdown with the same options
            function createLanguageSelect() {
                languageCount++;
                const newLanguageSelect = document.createElement('div');
                newLanguageSelect.classList.add('mb-3');
                newLanguageSelect.innerHTML = `
                    <select name="participants_name[]" class="form-control">
                        <option value="">Select next person</option>
                        ${optionsHTML}  <!-- Insert the PHP generated options -->
                    </select>
                `;
                languagesContainer.appendChild(newLanguageSelect);
            }

            // Event listener for the "add language" button
            addLanguageBtn.addEventListener('click', function () {
                createLanguageSelect();
            });
        });


    

    
    
    </script>



</body>

</html>
