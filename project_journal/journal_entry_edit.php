<?php
include_once('connection.php');
include_once ('navbar.php');

// Check if master_id is set in the GET request
if (isset($_POST['people_id_name'])) {
    $master_id = $_POST['people_id_name'];

    // Fetch existing journal entry data
    $query = "SELECT 
        jm.*, jl.label_name
        FROM journal_master jm
        LEFT JOIN journal_labels jl ON jm.label_id = jl.label_id
        WHERE jm.master_id = $master_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $journal = $result->fetch_assoc();

    // Fetch existing participants for this journal entry
    $participants_query = "SELECT 
        jm.*, 
        jp.*, 
        pm.*, 
        pg.*, 
        CONCAT(pm.people_name, ' - ', pg.group_name) AS people_complete
    FROM 
        journal_master jm
    LEFT JOIN 
        journal_participants jp ON jm.master_id = jp.journal_id
    LEFT JOIN 
        people_master pm ON jp.participant_name = pm.people_id
    LEFT JOIN 
        people_group pg ON pm.group_id = pg.group_id
        WHERE journal_id = $master_id
    ";
    $stmt2 = $conn->prepare($participants_query);
    $stmt2->execute();
    $participants_result = $stmt2->get_result();

} else {
    echo "Invalid journal ID!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Journal Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Journal Entry</h2>

    <!-- Edit Form -->
    <form method="post" action="journal_entry_update.php">
        <!-- Hidden field for master_id -->
        <input type="hidden" name="master_id" value="<?php echo $journal['master_id']; ?>">

        <!-- Participants Table -->
        <h4>Participants</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Participant Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="participants-list">
                <?php
                while ($participant = $participants_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $participant['people_complete'] . "</td>";
                    echo "<td><a href='journal_entry_delete_participant.php?participant_id=" . $participant['participant_id'] . "' class='btn btn-danger btn-sm'>Delete</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        
        <button type="button" class="btn btn-success" id="addParticipantBtn">Add Participant</button>

        <hr>
        <div class="mb-3">
            <label for="label_id" class="form-label">Label Name</label>
            <select name="label_id" class="form-control" required>
                <option value="<?php echo $journal['label_id']; ?>" selected>
                    <?php echo $journal['label_name']; ?>
                </option>
                <?php
                // Fetch all labels
                $label_query = "SELECT label_id, label_name FROM journal_labels";
                $label_result = $conn->query($label_query);
                while ($row = $label_result->fetch_assoc()) {
                    echo "<option value='{$row['label_id']}'>{$row['label_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo $journal['title']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="details" class="form-label">Details</label>
            <textarea name="details" class="form-control" rows="4" required><?php echo $journal['details']; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <input type="text" name="notes" class="form-control" value="<?php echo $journal['notes']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="notes2" class="form-label">Additional Notes</label>
            <input type="text" name="notes2" class="form-control" value="<?php echo $journal['notes2']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="time_start" class="form-label">Start Time</label>
            <input type="datetime-local" name="time_start" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($journal['time_start'])); ?>" required>
        </div>

        <div class="mb-3">
            <label for="time_end" class="form-label">End Time</label>
            <input type="datetime-local" name="time_end" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($journal['time_end'])); ?>" required>
        </div>
        
        <br>
        <button type="submit" class="btn btn-primary">Update Entry</button>
        <hr>
    </form>
</div>

<!-- Modal for adding participant -->
<div class="modal" tabindex="-1" id="addParticipantModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Participant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="newParticipantName" placeholder="Enter participant name">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveParticipantBtn">Save Participant</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('addParticipantBtn').addEventListener('click', function() {
        // Show the modal to add participant
        var modal = new bootstrap.Modal(document.getElementById('addParticipantModal'));
        modal.show();
    });

    document.getElementById('saveParticipantBtn').addEventListener('click', function() {
        var participantName = document.getElementById('newParticipantName').value;
        if (participantName) {
            var participantsList = document.getElementById('participants-list');
            var newRow = document.createElement('tr');
            newRow.innerHTML = `<td>${participantName}</td><td><button class="btn btn-danger btn-sm">Delete</button></td>`;
            participantsList.appendChild(newRow);

            // Close the modal
            var modal = bootstrap.Modal.getInstance(document.getElementById('addParticipantModal'));
            modal.hide();

            // Optionally, save the new participant using AJAX or a form submission
        }
    });
</script>

</body>
</html>
