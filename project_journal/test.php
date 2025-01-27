<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Languages Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Which computer languages do you know?</h2>
        <form id="languagesForm">
            <div id="languagesContainer">
                <!-- Initial dropdown -->
                <div class="mb-3">
                    <select class="form-select" id="language1" name="languages[]">
                        <option value="none" selected>None</option>
                        <option value="html">HTML</option>
                        <option value="css">CSS</option>
                        <option value="javascript">JavaScript</option>
                        <option value="php">PHP</option>
                        <option value="python">Python</option>
                        <!-- Add more languages as needed -->
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-success mt-2" id="addLanguageBtn">+</button>
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript to dynamically add more dropdowns
        document.addEventListener('DOMContentLoaded', () => {
            const languagesContainer = document.getElementById('languagesContainer');
            let languageCount = 1;

            // Function to create a new dropdown
            function createLanguageSelect() {
                languageCount++;
                const newLanguageSelect = document.createElement('div');
                newLanguageSelect.classList.add('mb-3');
                newLanguageSelect.innerHTML = `
                    <select class="form-select" id="language${languageCount}" name="languages[]">
                        <option value="none" selected>None</option>
                        <option value="html">HTML</option>
                        <option value="css">CSS</option>
                        <option value="javascript">JavaScript</option>
                        <option value="php">PHP</option>
                        <option value="python">Python</option>
                        <!-- Add more languages as needed -->
                    </select>
                `;
                languagesContainer.appendChild(newLanguageSelect);
            }

            // Event listener for the "add language" button
            document.getElementById('addLanguageBtn').addEventListener('click', function () {
                createLanguageSelect();
            });
        });
    </script>
</body>
</html>
