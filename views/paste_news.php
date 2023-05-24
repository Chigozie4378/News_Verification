<!DOCTYPE html>
<html>

<head>
    <title>News Classifier</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .centered-tabs {
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
                <a class="nav-link active" href="paste_news.php">Paste a News</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="upload.php">Upload a News</a>
            </li>
        </ul>

        <h1>News Classifier</h1>
        <form id="news-form">
            <div class="form-group">
                <label for="news">Paste News</label>
                <textarea class="form-control" id="news" rows="10"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <div class="mt-2">
            <!-- Add the loader image here -->
            <img class="loader text-primary" style="height: 30px;" src="../image/Rounded blocks.gif" alt="Loading...">
        </div>
        <div class="card card-primary mt-4" id="result">

        </div>
    </div>

    <script>
        $('#news-form').on('submit', function(e) {
            e.preventDefault();
            $(".loader").show(); // Show the loader when button is clicked
            if ($("#news").val() != "") {
                var news = $('#news').val();
                $.ajax({
                    url: 'http://localhost:5000/predict',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        news: news
                    }),
                    success: function(data) {
                        // If training is done, display success message
                        var successMessage = '<div class="card-body" style="text-transform: capitalize;"><b>Subject: </b>' + data.category + ' <br/><b>Authencity: </b>' + (data.is_real ? "Real" : "Fake") + '</div>';
                        $("#result").html(successMessage);
                        $(".loader").hide(); // Hide the loader when the AJAX request completes
                        $('#news').val('');
                    },
                    error: function() {
                        var errorMessage = '<div class="card-body"><div class="alert alert-warning"><strong>Warning!</strong> Train a Model to Verify News.</div> ';
                        $("#result").html(errorMessage);
                        $(".loader").hide(); // Hide the loader when an error occurs
                        $("#result").prop('disabled', false);
                    }
                });
            } else {
                var errorMessage = '<div class="card-body"><div class="alert alert-warning"><strong>Warning!</strong> You must Upload a News to Verify</div> ';
                $("#result").html(errorMessage);
                
                $(".loader").hide();
            }
        });
    </script>
</body>

</html>