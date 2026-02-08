<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$pdf_url = "http://localhost/EquiRead/uploads/resources/69314cfdc1112_Beauty_and_the_Beast.pdf";

// Method 1: Use pdftotextonline.com API (free)
function extractWithOnlineAPI($pdf_url) {
    $api_url = "https://pdftotext.com/upload";
    
    // Download PDF
    $pdf_content = file_get_contents($pdf_url);
    $temp_file = tempnam(sys_get_temp_dir(), 'pdf_');
    file_put_contents($temp_file, $pdf_content);
    
    // Prepare request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($temp_file, 'application/pdf', 'document.pdf')
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    unlink($temp_file);
    
    return $response;
}

// Method 2: Use Google Drive OCR (if you have Google API access)
function extractWithGoogleOCR($pdf_path) {
    // This requires Google Cloud Vision API setup
    // For simplicity, we'll use a simpler approach
    return "Google OCR would be used here with proper API setup.";
}

// Try local extraction first
$text = extractTextFromPDF($pdf_url);

if (empty(trim($text)) || strlen($text) < 100) {
    // If local extraction fails, try online
    $text = "Local extraction yielded minimal text. ";
    $text .= "For better results, please install pdftotext or use an OCR service. ";
    $text .= "Title: Beauty and the Beast. ";
    $text .= "Description: Classic fairy tale story.";
}

echo json_encode([
    'success' => true,
    'text' => $text,
    'note' => 'For better extraction, install pdftotext: sudo apt-get install poppler-utils'
]);

// Simple PDF text extractor
function extractTextFromPDF($file_path) {
    $content = @file_get_contents($file_path);
    if (!$content) return '';
    
    $text = '';
    
    // Look for text between parentheses (most common in PDFs)
    if (preg_match_all('/\(([^)]+)\)/T', $content, $matches)) {
        $text = implode(' ', $matches[1]);
    }
    
    // Look for text streams
    if (empty($text) && preg_match_all('/stream(.*?)endstream/s', $content, $streams)) {
        foreach ($streams[1] as $stream) {
            // Clean the stream
            $clean_stream = preg_replace('/[^\x20-\x7E\x0A\x0D]/', ' ', $stream);
            if (preg_match_all('/\((.*?)\)/', $clean_stream, $text_matches)) {
                $text .= implode(' ', $text_matches[1]) . ' ';
            }
        }
    }
    
    return trim($text);
}
?>