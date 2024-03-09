<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Generator</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* Add custom styles here */
    html,
    body {
        height: 100%;
        margin: 0;
    }

    body {
        background-color: #d0eaf8;
    }

    .word-lists ul {
        font-size: 2rem;
        /* Adjust the font size as needed */
    }

    /* Adjust the margin below the form */
    #wordForm {
        margin-bottom: 30px;
        /* Space below the button/form */
    }

    ul.no-bullets {
        list-style-type: none;
        font-size: 2rem;
    }

    .card img {
        margin-top: 15px;
        margin-bottom: 15px;
    }



    .footbg {
        background-color: #368cbf;
        color: white;
    }

    footer a {
        color: white;
    }

    footer a:hover {
        color: #ccc;
    }

    </style>
</head>

<body>
    <div class="container mb-5">
        <div class="card" style="color: white; background-color: #368cbf;  margin-top:30px;">
            <!-- Blue panel using a card -->
            <div class="card-body">
                <div class="text-center" style="margin-top: 30px;">
                    <h1 class="card-title">Esl-Ology: Match English and Spanish Words</h1>
                </div>
                <!-- Fun image goes here -->
                <div class="text-center">
                    <img src="Fun_55.webp" class="img-fluid" alt="Fun Image"
                        style="max-width: 100%; height: auto; margin-top: 15px; margin-bottom: 15px;">
                </div> <!-- Form begins here -->
                <form id="wordForm" action="generate-exercises.php" method="post">
                    <div class="form-group">
                        <label for="wordList" style="font-size: 1.6em;">To create a simple exercise to match English
                            words with their Spanish equivalents, enter a list of English words (comma-separated) in the
                            box below.</label>
                        <textarea class="form-control" style="font-size: 1.6em;" id="wordList" name="words"
                            rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-secondary">MAKE A MATCH</button>
                </form>
            </div>
        </div>
        <div id="exercisesContainer"></div>
    </div>
    <footer class="footbg text-center text-lg-start mt-5">
        <div class=" text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            Built with the help of Grimoire GPT & Bootstrap ©<?php echo date("Y"); ?> Copyright <a
                href="http://www.esl-ology.com" target="_blank">Esl-ology.com</a> &
            copyright owners.
        </div>
    </footer>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('#wordForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log("AJAX request successful. Response received: ", response);

                // Hide the form and any other content you don't need
                $("#wordForm").hide(); // Hides the form
                $(".card-title").hide(); // Hides the title
                $(".text-center").hide(); // Hides the image

                // Clears the card body
                $(".card-body").html('');

                // Create a row container
                let contentHtml = '<div class="row">';

                // Columns for English and Spanish words
                let englishColumnHtml =
                    '<div class="col-md-6"><h4></h4><ul class="no-bullets">';
                let spanishColumnHtml =
                    '<div class="col-md-6"><h4></h4><ul class="no-bullets">';

                // Populate English words
                response.english_words.forEach(word => {
                    englishColumnHtml += `<li>${word.trim()}</li>`;
                });

                if (response.spanish_words && response.spanish_words.length > 0) {
                    let translationLines = response.spanish_words[0].split("\n");
                    let spanishWords = translationLines.map(line => {
                        let parts = line.includes(" - ") ? line.split(" - ") : line
                            .split(": ");
                        return parts.length >= 2 ? parts[1].trim() : null;
                    }).filter(word => word !== null);

                    // Randomize Spanish words
                    spanishWords.sort(() => 0.5 - Math.random());
                    spanishWords.forEach(word => {
                        spanishColumnHtml += `<li>${word}</li>`;
                    });
                }

                englishColumnHtml += '</ul></div>';
                spanishColumnHtml += '</ul></div>';

                contentHtml += englishColumnHtml + spanishColumnHtml;
                contentHtml += '</div>'; // Close the row

                // Append the updated content to the card body
                $(".card-body").append(contentHtml);
            },
            error: function(xhr, status, error) {
                console.error("Error occurred: ", error);
                $('#exercisesContainer').html(
                    '<p>An error occurred while generating exercises.</p>');
            }
        });
    });
});
</script>


</body>

</html>
