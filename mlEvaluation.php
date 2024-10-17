<?php
// Load question data from JSON file
$jsonData = file_get_contents('ml.json');
$questionData = json_decode($jsonData, true);

$question = $questionData['question'];
$idealAnswer = $questionData['ideal_answer'];
$idealRating = $questionData['ideal_rating'];
$keywords = $questionData['keywords'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answer the Question</title>
</head>
<body>
    <h1>Answer the Question</h1>
    <form method="post" action="">
        <p><?php echo $question; ?></p>
        <textarea name="response" rows="10" cols="50" maxlength="500"></textarea><br><br>
        <input type="submit" value="Submit">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $response = $_POST['response'];

        // Check if response is empty
        if (empty($response)) {
            echo "<p>Please provide a response.</p>";
            exit;
        }

        // Load the C library and call the NLP function
        $ffi = FFI::cdef(
            "double analyze_response(const char *response, const char *ideal_answer, const char **keywords, int keyword_count);", 
            __DIR__ . "/libnlp.dll"
        );        

        // Convert PHP string array to C array of char*
        $keywordsArray = FFI::new("const char*[" . count($keywords) . "]");
        foreach ($keywords as $index => $keyword) {
            $keywordsArray[$index] = FFI::new("char[256]", false);
            FFI::memcpy($keywordsArray[$index], $keyword, strlen($keyword));
        }

        // Perform analysis
        $rating = $ffi->analyze_response($response, $idealAnswer, $keywordsArray, count($keywords));

        // Display the rating
        echo "<h1>Your response has been rated: " . round($rating, 2) . " / 10</h1>";
    }
    ?>
</body>
</html>
