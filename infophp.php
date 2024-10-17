<?php
// Load the FFI

phpinfo();

try {
    $ffi = FFI::cdef(
        "int analyze_sentiment(const char*);",
        __DIR__ . "/libsentiment.dll"
    );

    // Example text
    $text = "Sample response text";

    // Call the function from the shared library
    $score = $ffi->analyze_sentiment($text);

    echo "Sentiment score: " . $score;
} catch (FFI\Exception $e) {
    echo "FFI Exception: " . $e->getMessage();
}
?>
