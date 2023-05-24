<!DOCTYPE html>
<html>

<head>
    <title>Train Model</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .centered-tabs {
            display: flex;
            justify-content: center;
        }

        .centered-navbar {
            display: flex;
            justify-content: center;
        }

        .loader {
            display: none;
            margin: auto;
        }
    </style>
</head>

<body>

    <?php include_once "../includes/navbar.php" ?>
    <div class="container" style="margin-top:60px; position:sticky; top: 60px;">
        <ul class="nav nav-tabs centered-tabs">
            <li class="nav-item">
                <a class="nav-link" href="train_logistic.php">Train Logistic Model</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="train_decision_tree.php">Train Decision Tree</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="train_random_forest.php">Train Random Forest</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="train_support_vector.php">Train Linear Support Vector</a>
            </li>

        </ul>
        <h1>Train Linear Support Model</h1>
        <button class="btn btn-primary" id="train-button">Train</button>
        <div class="mt-2">
            <!-- Add the loader image here -->
            <img class="loader text-primary" src="../image/30.gif" alt="Loading...">
        </div>

        <div class="card mt-4" id="result">
           
        </div>
    </div>

    <script>
        $("#train-button").click(function() {
            $(this).prop('disabled', true);
            $(".loader").show();  // Show the loader when button is clicked
            $("#result").html(""); // Clear the result div

            $.ajax({
                url: 'http://localhost:5000/train_support_vector',
                type: 'POST',
                success: function(data) {
                    // If training is done, display success message
                    var successMessage = '<div class="card-body"><div class="alert alert-success"><strong>Success!</strong> Model Training Finished</div> ';
                    successMessage += '<h6><strong>Category Accuracy:</strong> ' + data.category_accuracy + '</h6>';
                    successMessage += '<h6><strong>Model Accuracy:</strong> ' + data.model_accuracy + '</h6>';
                    $("#result").html(successMessage);
                    $("#train-button").prop('disabled', false);
                    $(".loader").hide();  // Hide the loader when the AJAX request completes
                },
                error: function() {
                    var errorMessage = '<div class="card-body"><div class="alert alert-danger"><strong>Danger!</strong> An error occurred while training the model.</div> ';
                    $("#result").html(errorMessage);
                    $("#train-button").prop('disabled', false);
                    $(".loader").hide();  // Hide the loader when an error occurs
                },
                xhr: function() {
                    // Get new xhr object using default factory
                    var xhr = new window.XMLHttpRequest();
                    // Setup our listener to process compeleted requests
                    xhr.onreadystatechange = function() {
                        // Only run if the request is complete
                        if (xhr.readyState !== 4) return;
                        // Process our return data
                        if (xhr.status >= 200 && xhr.status < 300) {
                            // Run when the request is successful
                        } else {
                            // Run when it's not
                            console.log('error occurred during ajax call');
                        }
                    };
                    return xhr;
                }
            });
        });
    </script>
</body>

</html>
