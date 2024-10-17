#include <stdio.h>
#include <string.h>
#include <ctype.h>
#include <math.h>

// Helper function to convert a string to lowercase
void to_lowercase(char *str) {
    while (*str) {
        *str = tolower((unsigned char) *str);
        str++;
    }
}

// Helper function to tokenize and count words
void tokenize_and_count(const char *text, char words[][256], int *word_counts, int *num_words) {
    char text_copy[1024];
    strcpy(text_copy, text);
    to_lowercase(text_copy);

    char *token = strtok(text_copy, " .,!?");  // Tokenize by common delimiters
    while (token != NULL) {
        int found = 0;
        // Check if the token already exists in the words array
        for (int i = 0; i < *num_words; i++) {
            if (strcmp(words[i], token) == 0) {
                word_counts[i]++;
                found = 1;
                break;
            }
        }
        // If not found, add it as a new word
        if (!found && *num_words < 256) {
            strcpy(words[*num_words], token);
            word_counts[*num_words] = 1;
            (*num_words)++;
        }
        token = strtok(NULL, " .,!?");
    }
}

// Function to compute cosine similarity between two texts
double cosine_similarity(const char *text1, const char *text2) {
    char words1[256][256], words2[256][256];
    int counts1[256] = {0}, counts2[256] = {0};
    int num_words1 = 0, num_words2 = 0;

    // Tokenize and count words in both texts
    tokenize_and_count(text1, words1, counts1, &num_words1);
    tokenize_and_count(text2, words2, counts2, &num_words2);

    // Create a combined word vector
    int total_words = num_words1 + num_words2;
    char all_words[512][256];
    int all_counts1[512] = {0}, all_counts2[512] = {0};
    int combined_word_count = 0;

    // Copy words from text1
    for (int i = 0; i < num_words1; i++) {
        strcpy(all_words[combined_word_count], words1[i]);
        all_counts1[combined_word_count] = counts1[i];
        combined_word_count++;
    }

    // Copy words from text2, updating counts if words overlap
    for (int i = 0; i < num_words2; i++) {
        int found = 0;
        for (int j = 0; j < combined_word_count; j++) {
            if (strcmp(all_words[j], words2[i]) == 0) {
                all_counts2[j] = counts2[i];
                found = 1;
                break;
            }
        }
        if (!found) {
            strcpy(all_words[combined_word_count], words2[i]);
            all_counts2[combined_word_count] = counts2[i];
            combined_word_count++;
        }
    }

    // Calculate the dot product and magnitudes for cosine similarity
    double dot_product = 0.0, magnitude1 = 0.0, magnitude2 = 0.0;
    for (int i = 0; i < combined_word_count; i++) {
        dot_product += all_counts1[i] * all_counts2[i];
        magnitude1 += all_counts1[i] * all_counts1[i];
        magnitude2 += all_counts2[i] * all_counts2[i];
    }

    // Compute cosine similarity
    if (magnitude1 == 0.0 || magnitude2 == 0.0) {
        return 0.0;  // Avoid division by zero if one vector is empty
    }
    return dot_product / (sqrt(magnitude1) * sqrt(magnitude2));
}

// Calculate the number of keywords found in the response
int keyword_match_count(const char *response, const char **keywords, int keyword_count) {
    int match_count = 0;
    char response_copy[1024];
    strcpy(response_copy, response);
    to_lowercase(response_copy);

    for (int i = 0; i < keyword_count; i++) {
        char keyword_copy[256];
        strcpy(keyword_copy, keywords[i]);
        to_lowercase(keyword_copy);
        
        if (strstr(response_copy, keyword_copy) != NULL) {
            match_count++;
        }
    }

    return match_count;
}

// Main function to analyze the response
double analyze_response(const char *response, const char *ideal_answer, const char **keywords, int keyword_count) {
    // Check keyword matches
    int matches = keyword_match_count(response, keywords, keyword_count);
    double keyword_score = (double) matches / keyword_count;

    // Calculate cosine similarity to ideal answer
    double cosine_sim_score = cosine_similarity(response, ideal_answer);

    // Combine scores for a final rating
    double final_rating = (keyword_score + cosine_sim_score) / 2 * 10;  // Scale to 1-10 rating
    return final_rating;
}

// Example usage
int main() {
    const char *keywords[] = {"important", "knowledge", "learning"};
    const char *response = "Learning is the key to gaining knowledge. It's important to stay curious.";
    const char *ideal_answer = "It is important to always value knowledge and stay committed to learning.";

    double rating = analyze_response(response, ideal_answer, keywords, 3);
    printf("Response rating: %.2f\n", rating);
    return 0;
}
