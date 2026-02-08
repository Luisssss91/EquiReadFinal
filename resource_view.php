<?php
include 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: resources.php");
    exit;
}

$resource_id = intval($_GET['id']);

// Get resource details
$sql = "SELECT r.*, u.full_name as uploader_name, c.name as category_name, c.color as category_color 
        FROM tbl_resources r 
        LEFT JOIN tbl_users u ON r.uploaded_by = u.user_id 
        LEFT JOIN tbl_categories c ON r.category = c.name 
        WHERE r.resource_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $resource_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$resource = mysqli_fetch_assoc($result);

if (!$resource) {
    header("location: resources.php");
    exit;
}

// Get related resources
$related_sql = "SELECT * FROM tbl_resources WHERE category = ? AND resource_id != ? ORDER BY upload_date DESC LIMIT 4";
$related_stmt = mysqli_prepare($conn, $related_sql);
mysqli_stmt_bind_param($related_stmt, "si", $resource['category'], $resource_id);
mysqli_stmt_execute($related_stmt);
$related_resources = mysqli_stmt_get_result($related_stmt);
?>

<?php include 'includes/header.php'; ?>

<style>
/* TTS Styles for full content */
.full-content-display {
    text-align: left;
    background-color: white;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    max-height: 500px;
    overflow-y: auto;
    margin: 15px 0;
    line-height: 1.6;
}

.full-content-notice {
    margin-bottom: 15px;
    padding: 10px;
    background-color: #e7f3ff;
    border-radius: 6px;
    border-left: 4px solid #007bff;
}

.full-content-notice h4 {
    margin-top: 0;
    color: #0056b3;
}

.extracted-text {
    white-space: pre-wrap;
    word-wrap: break-word;
    font-family: monospace;
    font-size: 14px;
}

/* TTS Controls */
.tts-controls {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    margin: 15px 0;
}

.tts-speed, .tts-voice {
    display: flex;
    align-items: center;
    gap: 5px;
}

.tts-status {
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 6px;
    margin: 10px 0;
    text-align: center;
}

.tts-content-info {
    margin-top: 10px;
    color: #666;
    text-align: center;
}

.resource-tts-controls {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
    border: 1px solid #dee2e6;
}

.resource-tts-controls h3 {
    margin-top: 0;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}
</style>

<div class="main-content">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Home</a> &gt;
            <a href="resources.php">Resources</a> &gt;
            <span><?php echo htmlspecialchars($resource['title']); ?></span>
        </nav>

        <div class="resource-detail">
            <div class="resource-main">
                <div class="resource-header">
                    <div class="resource-badges">
                        <span class="resource-type" style="background: <?php echo $resource['category_color'] ?? '#ff85a2'; ?>">
                            <i class="fas fa-file-<?php echo getFileTypeIcon($resource['file_type']); ?>"></i>
                            <?php echo strtoupper($resource['file_type']); ?>
                        </span>
                        <span class="resource-category"><?php echo htmlspecialchars($resource['category_name'] ?? 'General'); ?></span>
                        <?php if($resource['is_featured']): ?>
                            <span class="featured-badge"><i class="fas fa-star"></i> Featured</span>
                        <?php endif; ?>
                    </div>
                    
                    <h1><?php echo htmlspecialchars($resource['title']); ?></h1>
                    
                    <div class="resource-meta">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            Uploaded by <?php echo htmlspecialchars($resource['uploader_name'] ?? 'System'); ?>
                        </div>
                        <div class="meta-item">
                            <i class="far fa-calendar"></i>
                            <?php echo date('F j, Y', strtotime($resource['upload_date'])); ?>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-download"></i>
                            <?php echo $resource['downloads_count']; ?> downloads
                        </div>
                        <?php if($resource['file_size']): ?>
                        <div class="meta-item">
                            <i class="fas fa-weight"></i>
                            <?php echo formatFileSize($resource['file_size']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Text-to-Speech Controls -->
                <div class="resource-tts-controls">
                    <h3><i class="fas fa-volume-up"></i> Listen to Resource</h3>
                    <div class="tts-controls">
                        <button id="playTTS" class="btn btn-primary">
                            <i class="fas fa-play"></i> Play Description
                        </button>
                        <button id="pauseTTS" class="btn btn-outline">
                            <i class="fas fa-pause"></i> Pause
                        </button>
                        <button id="stopTTS" class="btn btn-outline">
                            <i class="fas fa-stop"></i> Stop
                        </button>
                        <button id="resumeTTS" class="btn btn-success" style="display: none;">
                            <i class="fas fa-redo"></i> Resume
                        </button>
                        <div class="tts-speed">
                            <label for="ttsRate">Speed:</label>
                            <select id="ttsRate" class="form-control-sm">
                                <option value="0.5">0.5x</option>
                                <option value="0.75">0.75x</option>
                                <option value="1" selected>Normal</option>
                                <option value="1.25">1.25x</option>
                                <option value="1.5">1.5x</option>
                                <option value="2">2x</option>
                            </select>
                        </div>
                        <div class="tts-voice">
                            <label for="ttsVoice">Voice:</label>
                            <select id="ttsVoice" class="form-control-sm"></select>
                        </div>
                        <div class="tts-volume">
                            <label for="ttsVolume">Volume:</label>
                            <select id="ttsVolume" class="form-control-sm">
                                <option value="0.5">Low</option>
                                <option value="0.75">Medium</option>
                                <option value="1" selected>Normal</option>
                                <option value="1.25">High</option>
                                <option value="1.5">Max</option>
                            </select>
                        </div>
                        <?php if(strtolower($resource['file_type']) === 'pdf' && $resource['file_path'] && file_exists($resource['file_path'])): ?>
                        <button id="extractFullContent" class="btn btn-info">
                            <i class="fas fa-file-alt"></i> Read Full PDF
                        </button>
                        <?php endif; ?>
                    </div>
                    <div class="tts-status" id="ttsStatus">
                        Ready to read description
                    </div>
                    <div class="tts-content-info">
                        <small id="contentStats"></small>
                    </div>
                </div>

                <div class="resource-description">
                    <h3><i class="fas fa-align-left"></i> Description</h3>
                    <div id="readableContent">
                        <p><?php echo nl2br(htmlspecialchars($resource['description'])); ?></p>
                    </div>
                </div>

                <?php if(!empty($resource['tags'])): ?>
                <div class="resource-tags">
                    <h3><i class="fas fa-tags"></i> Tags</h3>
                    <div class="tags-list">
                        <?php
                        $tags = explode(',', $resource['tags']);
                        foreach($tags as $tag):
                            $tag = trim($tag);
                            if(!empty($tag)):
                        ?>
                        <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="resource-actions">
                    <?php if($resource['file_path'] && file_exists($resource['file_path'])): ?>
                    <a href="<?php echo $resource['file_path']; ?>" class="btn btn-primary btn-large" download onclick="trackDownload(<?php echo $resource_id; ?>)">
                        <i class="fas fa-download"></i> Download Resource
                    </a>
                    <?php else: ?>
                    <button class="btn btn-secondary btn-large" disabled>
                        <i class="fas fa-exclamation-circle"></i> File Not Available
                    </button>
                    <?php endif; ?>
                    
                    <button class="btn btn-outline" id="shareResource">
                        <i class="fas fa-share-alt"></i> Share
                    </button>
                    
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="manage_resources.php?edit=<?php echo $resource_id; ?>" class="btn btn-outline">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="resource-sidebar">
                <div class="sidebar-card">
                    <h3><i class="fas fa-info-circle"></i> Resource Info</h3>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-label">Type:</span>
                            <span class="info-value"><?php echo ucfirst($resource['file_type']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Category:</span>
                            <span class="info-value"><?php echo htmlspecialchars($resource['category_name'] ?? 'General'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Upload Date:</span>
                            <span class="info-value"><?php echo date('M j, Y', strtotime($resource['upload_date'])); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Downloads:</span>
                            <span class="info-value"><?php echo $resource['downloads_count']; ?></span>
                        </div>
                        <?php if($resource['file_size']): ?>
                        <div class="info-item">
                            <span class="info-label">File Size:</span>
                            <span class="info-value"><?php echo formatFileSize($resource['file_size']); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <span class="info-label">TTS Support:</span>
                            <span class="info-value">
                                <?php if(strtolower($resource['file_type']) === 'pdf'): ?>
                                    <i class="fas fa-check-circle text-success"></i> Full PDF support
                                <?php else: ?>
                                    <i class="fas fa-info-circle"></i> Description only
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="sidebar-card">
                    <h3><i class="fas fa-upload"></i> Uploader</h3>
                    <div class="uploader-info">
                        <div class="uploader-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="uploader-details">
                            <h4><?php echo htmlspecialchars($resource['uploader_name'] ?? 'System'); ?></h4>
                            <p>Resource Contributor</p>
                        </div>
                    </div>
                </div>

                <?php if(mysqli_num_rows($related_resources) > 0): ?>
                <div class="sidebar-card">
                    <h3><i class="fas fa-bookmark"></i> Related Resources</h3>
                    <div class="related-resources">
                        <?php while($related = mysqli_fetch_assoc($related_resources)): ?>
                        <a href="resource_view.php?id=<?php echo $related['resource_id']; ?>" class="related-item">
                            <div class="related-icon">
                                <i class="fas fa-file-<?php echo getFileTypeIcon($related['file_type']); ?>"></i>
                            </div>
                            <div class="related-details">
                                <h4><?php echo htmlspecialchars($related['title']); ?></h4>
                                <span class="related-type"><?php echo ucfirst($related['file_type']); ?></span>
                            </div>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Share functionality
document.getElementById('shareResource').addEventListener('click', function() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo addslashes($resource['title']); ?>',
            text: 'Check out this resource from EquiRead Digital Library',
            url: window.location.href
        });
    } else {
        const tempInput = document.createElement('input');
        tempInput.value = window.location.href;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        alert('Link copied to clipboard!');
    }
});

// Text-to-Speech functionality
let speech = null;
let currentUtterance = null;
let voices = [];
let fullPDFText = '';
let lastCharIndex = 0;
let isReadingFullContent = false;

// Initialize TTS
function initTTS() {
    if ('speechSynthesis' in window) {
        speech = window.speechSynthesis;
        
        // Load voices
        function loadVoices() {
            voices = speech.getVoices();
            const voiceSelect = document.getElementById('ttsVoice');
            if (!voiceSelect) return;
            
            voiceSelect.innerHTML = '';
            
            voices.forEach((voice, i) => {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `${voice.name} (${voice.lang})`;
                voiceSelect.appendChild(option);
            });
        }
        
        loadVoices();
        if (speechSynthesis.onvoiceschanged !== undefined) {
            speechSynthesis.onvoiceschanged = loadVoices;
        }
        
        // Set initial content to description
        updateContentStats(document.getElementById('readableContent').textContent);
        
    } else {
        // TTS not supported
        document.querySelector('.resource-tts-controls').innerHTML = `
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                Text-to-speech is not supported in your browser.
            </div>
        `;
    }
}

// Update content statistics
function updateContentStats(content) {
    if (!content) return;
    
    const charCount = content.length;
    const wordCount = content.split(/\s+/).filter(word => word.length > 0).length;
    const readingTime = Math.ceil(wordCount / 200);
    
    const statsElement = document.getElementById('contentStats');
    if (statsElement) {
        statsElement.textContent = 
            `Characters: ${charCount.toLocaleString()} | Words: ${wordCount.toLocaleString()} | Estimated time: ${readingTime} min`;
    }
}

// Function to speak content
function speakContent(startFromIndex = 0) {
    if (speech.speaking) {
        speech.cancel();
    }
    
    let textToSpeak = '';
    
    if (isReadingFullContent && fullPDFText) {
        // Read full PDF content
        if (startFromIndex > 0 && startFromIndex < fullPDFText.length) {
            textToSpeak = fullPDFText.substring(startFromIndex);
        } else {
            textToSpeak = fullPDFText;
        }
    } else {
        // Read description only
        textToSpeak = document.getElementById('readableContent').textContent;
    }
    
    if (!textToSpeak || textToSpeak.length === 0) {
        updateStatus('No content available to read');
        return;
    }
    
    currentUtterance = new SpeechSynthesisUtterance(textToSpeak);
    
    // Set voice
    const voiceSelect = document.getElementById('ttsVoice');
    if (voiceSelect && voices.length > 0) {
        const selectedVoiceIndex = parseInt(voiceSelect.value);
        if (!isNaN(selectedVoiceIndex) && voices[selectedVoiceIndex]) {
            currentUtterance.voice = voices[selectedVoiceIndex];
        }
    }
    
    // Set rate
    const rateSelect = document.getElementById('ttsRate');
    if (rateSelect) {
        currentUtterance.rate = parseFloat(rateSelect.value) || 1;
    }
    
    // Set volume
    const volumeSelect = document.getElementById('ttsVolume');
    if (volumeSelect) {
        currentUtterance.volume = parseFloat(volumeSelect.value) || 1;
    }
    
    // Event listeners
    const playBtn = document.getElementById('playTTS');
    const pauseBtn = document.getElementById('pauseTTS');
    const stopBtn = document.getElementById('stopTTS');
    const resumeBtn = document.getElementById('resumeTTS');
    const statusDiv = document.getElementById('ttsStatus');
    
    function updateStatus(message) {
        if (statusDiv) statusDiv.textContent = message;
    }
    
    // Track position for resuming
    let currentCharIndex = startFromIndex;
    
    currentUtterance.onstart = function() {
        updateStatus(isReadingFullContent ? 'Reading full PDF content...' : 'Reading description...');
        if (playBtn) playBtn.disabled = true;
        if (pauseBtn) pauseBtn.disabled = false;
        if (stopBtn) stopBtn.disabled = false;
        if (resumeBtn) resumeBtn.style.display = 'none';
    };
    
    currentUtterance.onend = function() {
        if (startFromIndex > 0) {
            updateStatus('Finished reading from where you left off');
        } else {
            updateStatus(isReadingFullContent ? 'Finished reading full content' : 'Finished reading description');
        }
        if (playBtn) playBtn.disabled = false;
        if (pauseBtn) pauseBtn.disabled = true;
        if (stopBtn) stopBtn.disabled = true;
        if (resumeBtn) resumeBtn.style.display = 'none';
        lastCharIndex = 0;
    };
    
    currentUtterance.onerror = function(event) {
        updateStatus('Error: ' + event.error);
        if (playBtn) playBtn.disabled = false;
        if (pauseBtn) pauseBtn.disabled = true;
        if (stopBtn) stopBtn.disabled = true;
    };
    
    // Track position for resuming
    currentUtterance.onboundary = function(event) {
        if (event.name === 'word') {
            currentCharIndex = startFromIndex + event.charIndex + event.charLength;
            lastCharIndex = currentCharIndex;
        }
    };
    
    speech.speak(currentUtterance);
}

// Extract and read full PDF content using PDF.js
document.getElementById('extractFullContent')?.addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    const statusDiv = document.getElementById('ttsStatus');
    const playBtn = document.getElementById('playTTS');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Extracting...';
    
    if (statusDiv) {
        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Extracting FULL text from PDF...';
    }
    
    // Load PDF.js if not already loaded
    if (typeof pdfjsLib === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
        
        script.onload = function() {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            extractPDFContent(btn, originalText);
        };
        
        script.onerror = function() {
            if (statusDiv) {
                statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> Failed to load PDF library';
            }
            btn.disabled = false;
            btn.innerHTML = originalText;
            alert('Failed to load PDF library. Please check your internet connection.');
        };
        
        document.head.appendChild(script);
    } else {
        extractPDFContent(btn, originalText);
    }
});

function extractPDFContent(btn, originalText) {
    const statusDiv = document.getElementById('ttsStatus');
    const filePath = '<?php echo $resource["file_path"]; ?>';
    
    // Load the PDF
    pdfjsLib.getDocument(filePath).promise.then(function(pdf) {
        let fullText = `Title: <?php echo addslashes($resource['title']); ?>. `;
        fullText += `Description: <?php echo addslashes($resource['description']); ?>. `;
        fullText += `Content: `;
        
        let pagePromises = [];
        
        // Extract text from each page
        for (let i = 1; i <= pdf.numPages; i++) {
            pagePromises.push(
                pdf.getPage(i).then(function(page) {
                    return page.getTextContent().then(function(textContent) {
                        let pageText = '';
                        textContent.items.forEach(function(item) {
                            pageText += item.str + ' ';
                        });
                        return pageText.trim();
                    });
                })
            );
        }
        
        // Combine all pages
        Promise.all(pagePromises).then(function(pages) {
            fullText += pages.join(' ');
            
            // Clean up text
            fullText = fullText.replace(/\s+/g, ' ').trim();
            
            // Store the full content
            fullPDFText = fullText;
            isReadingFullContent = true;
            
            // Update the displayed content
            const readableContent = document.getElementById('readableContent');
            if (readableContent) {
                // Store original description
                if (!readableContent.dataset.original) {
                    readableContent.dataset.original = readableContent.innerHTML;
                }
                
                // Show extracted content preview (not truncated for display)
                readableContent.innerHTML = `
                    <div class="full-content-notice">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            Successfully extracted ${pdf.numPages} pages from PDF
                        </div>
                        <h4><i class="fas fa-file-alt"></i> Full PDF Content:</h4>
                        <div class="extracted-text">
                            ${fullText.replace(/\n/g, '<br>')}
                        </div>
                        <button id="showDescription" class="btn btn-sm btn-outline mt-2">
                            <i class="fas fa-arrow-left"></i> Back to Description
                        </button>
                    </div>
                `;
                
                // Add event listener for back button
                document.getElementById('showDescription').addEventListener('click', function() {
                    readableContent.innerHTML = readableContent.dataset.original;
                    fullPDFText = '';
                    isReadingFullContent = false;
                    updateContentStats(readableContent.textContent);
                    
                    if (statusDiv) {
                        statusDiv.innerHTML = '<i class="fas fa-check-circle text-success"></i> Showing description';
                    }
                    
                    const playBtn = document.getElementById('playTTS');
                    if (playBtn) {
                        playBtn.innerHTML = '<i class="fas fa-play"></i> Play Description';
                    }
                });
            }
            
            // Update stats with FULL content
            updateContentStats(fullText);
            
            // Update status
            if (statusDiv) {
                statusDiv.innerHTML = `<i class="fas fa-check-circle text-success"></i> Full PDF content ready!`;
            }
            
            // Update play button
            const playBtn = document.getElementById('playTTS');
            if (playBtn) {
                playBtn.innerHTML = '<i class="fas fa-play"></i> Play Full PDF';
            }
            
            // Reset extract button
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Content Ready';
            
            // Auto-play after extraction
            setTimeout(() => {
                speakContent();
            }, 500);
            
            // Reset extract button after 3 seconds
            setTimeout(() => {
                btn.innerHTML = originalText;
            }, 3000);
            
        }).catch(function(error) {
            console.error('Error extracting pages:', error);
            
            if (statusDiv) {
                statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> Error extracting PDF content';
            }
            
            btn.disabled = false;
            btn.innerHTML = originalText;
            alert('Failed to extract content from PDF: ' + error.message);
        });
        
    }).catch(function(error) {
        console.error('Error loading PDF:', error);
        
        if (statusDiv) {
            statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> Error loading PDF';
        }
        
        btn.disabled = false;
        btn.innerHTML = originalText;
        alert('Failed to load PDF: ' + error.message);
    });
}

// Track download
function trackDownload(resourceId) {
    fetch('track_download.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'resource_id=' + resourceId
    }).catch(error => {
        console.error('Error tracking download:', error);
    });
}

// Initialize TTS when page loads
document.addEventListener('DOMContentLoaded', function() {
    initTTS();
    
    // Set up button event listeners
    const playBtn = document.getElementById('playTTS');
    const pauseBtn = document.getElementById('pauseTTS');
    const stopBtn = document.getElementById('stopTTS');
    const resumeBtn = document.getElementById('resumeTTS');
    const rateSelect = document.getElementById('ttsRate');
    const voiceSelect = document.getElementById('ttsVoice');
    const volumeSelect = document.getElementById('ttsVolume');
    const statusDiv = document.getElementById('ttsStatus');
    
    function updateStatus(message) {
        if (statusDiv) statusDiv.textContent = message;
    }
    
    if (playBtn) {
        playBtn.addEventListener('click', function() {
            if (speech.speaking && speech.paused) {
                speech.resume();
                updateStatus(isReadingFullContent ? 'Resumed reading full content' : 'Resumed reading description');
                playBtn.disabled = true;
                if (pauseBtn) pauseBtn.disabled = false;
                if (resumeBtn) resumeBtn.style.display = 'none';
            } else if (!speech.speaking) {
                speakContent();
            }
        });
    }
    
    if (pauseBtn) {
        pauseBtn.addEventListener('click', function() {
            if (speech.speaking && !speech.paused) {
                speech.pause();
                updateStatus('Paused');
                if (playBtn) playBtn.disabled = false;
                pauseBtn.disabled = true;
                if (resumeBtn && lastCharIndex > 0) {
                    resumeBtn.style.display = 'inline-block';
                }
            }
        });
    }
    
    if (resumeBtn) {
        resumeBtn.addEventListener('click', function() {
            if (lastCharIndex > 0) {
                speakContent(lastCharIndex);
            }
        });
    }
    
    if (stopBtn) {
        stopBtn.addEventListener('click', function() {
            speech.cancel();
            updateStatus('Stopped');
            if (playBtn) playBtn.disabled = false;
            if (pauseBtn) pauseBtn.disabled = true;
            if (resumeBtn) resumeBtn.style.display = 'none';
            lastCharIndex = 0;
        });
    }
    
    // Update rate when changed
    if (rateSelect) {
        rateSelect.addEventListener('change', function() {
            if (currentUtterance && speech.speaking) {
                currentUtterance.rate = parseFloat(this.value);
                speech.cancel();
                // Resume from where we were
                const resumeFrom = lastCharIndex > 0 ? lastCharIndex : 0;
                speakContent(resumeFrom);
            }
        });
    }
    
    // Update voice when changed
    if (voiceSelect) {
        voiceSelect.addEventListener('change', function() {
            if (currentUtterance && speech.speaking) {
                const selectedVoiceIndex = parseInt(this.value);
                if (!isNaN(selectedVoiceIndex) && voices[selectedVoiceIndex]) {
                    currentUtterance.voice = voices[selectedVoiceIndex];
                    speech.cancel();
                    // Resume from where we were
                    const resumeFrom = lastCharIndex > 0 ? lastCharIndex : 0;
                    speakContent(resumeFrom);
                }
            }
        });
    }
    
    // Update volume when changed
    if (volumeSelect) {
        volumeSelect.addEventListener('change', function() {
            if (currentUtterance && speech.speaking) {
                currentUtterance.volume = parseFloat(this.value);
                speech.cancel();
                // Resume from where we were
                const resumeFrom = lastCharIndex > 0 ? lastCharIndex : 0;
                speakContent(resumeFrom);
            }
        });
    }
    
    // Initial button states
    if (pauseBtn) pauseBtn.disabled = true;
    if (stopBtn) stopBtn.disabled = true;
    if (resumeBtn) resumeBtn.style.display = 'none';
});
</script>

<?php
// Helper functions
function getFileTypeIcon($file_type) {
    switch($file_type) {
        case 'pdf': return 'pdf';
        case 'doc': case 'docx': return 'word';
        case 'xls': case 'xlsx': return 'excel';
        case 'ppt': case 'pptx': return 'powerpoint';
        case 'jpg': case 'jpeg': case 'png': case 'gif': return 'image';
        case 'mp4': case 'avi': case 'mov': return 'video';
        case 'mp3': case 'wav': return 'audio';
        default: return 'alt';
    }
}

function formatFileSize($bytes) {
    if ($bytes == 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}
?>

<?php include 'includes/footer.php'; ?>