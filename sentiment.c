#include <stdio.h>
#include <string.h>

// A very simple sentiment analysis function
int analyze_sentiment(const char* text) {
    // Simple keyword-based sentiment analysis
    if (strstr(text, "love") || strstr(text, "happy") || strstr(text, "great")) {
        return 1; // Positive sentiment
    } else if (strstr(text, "hate") || strstr(text, "bad") || strstr(text, "terrible")) {
        return -1; // Negative sentiment
    } else {
        return 0; // Neutral or unrecognized
    }
}
