<!DOCTYPE html>
<html>

<head>
    <title>News Classifier</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script> <!-- Include pdf.js library -->

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
                <a class="nav-link" href="paste_news.php">Paste a News</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="upload.php">Upload a News</a>
            </li>
        </ul>

        <h1>News Classifier</h1>
        <form id="upload-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Upload News</label>
                <input type="file" class="form-control" id="file" name="file" accept=".txt,.pdf">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <div class="mt-2">
            <!-- Add the loader image here -->
            <img class="loader text-primary" style="height: 30px;" src="../image/Rounded blocks.gif" alt="Loading...">
        </div>
        <div class="card mt-4" id="result">

        </div>
    </div>

    <script>
        $('#upload-form').on('submit', function(e) {
            e.preventDefault();
            if ($("#file").val() != "") {
                var file = $('#file')[0].files[0];
                var fileType = file.name.split('.').pop();
                $(".loader").show(); // Show the loader when button is clicked
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (fileType === 'pdf') {
                        var pdfData = new Uint8Array(this.result);
                        pdfjsLib.getDocument({
                            data: pdfData
                        }).promise.then(function(pdf) {
                            var totalPages = pdf.numPages;
                            var countPromises = []; // collecting all page promises
                            for (var i = 1; i <= totalPages; i++) {
                                countPromises.push(pdf.getPage(i).then(function(page) {
                                    return page.getTextContent().then(function(textContent) {
                                        return textContent.items.map(function(item) {
                                            return item.str;
                                        }).join(' ');
                                    });
                                }));
                            }

                            Promise.all(countPromises).then(function(pageTexts) {
                                var contents = pageTexts.join('\n');
                                makeAjaxRequest(contents);
                            });
                        });
                    } else {
                        var contents = e.target.result;
                        makeAjaxRequest(contents);
                    }
                };

                if (fileType === 'pdf') {
                    reader.readAsArrayBuffer(file);
                } else {
                    reader.readAsText(file);
                }
            } else {
                var errorMessage = '<div class="card-body"><div class="alert alert-warning"><strong>Warning!</strong> You must Upload a News to Verify</div> ';
                $("#result").html(errorMessage);
                $(".loader").hide();
            }
        });

        function makeAjaxRequest(contents) {

            $.ajax({
                url: 'http://localhost:5000/predict',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    news: contents
                }),
                success: function(data) {
                    // If training is done, display success message
                    var successMessage = '<div class="card-body" style="text-transform: capitalize;"><b>Subject: </b>' + data.category + ' <br/><b>Authencity: </b>' + (data.is_real ? "Real" : "Fake") + '</div>';
                    $("#result").html(successMessage);
                    $(".loader").hide(); // Hide the loader when the AJAX request completes
                },
                error: function() {
                    var errorMessage = '<div class="card-body"><div class="alert alert-warning"><strong>Warning!</strong> Train a Model to Verify News.</div> ';
                    $("#result").html(errorMessage);
                    $(".loader").hide(); // Hide the loader when an error occurs
                    $("#result").prop('disabled', false);
                }
            });

        }
    </script>

</body>

</html>