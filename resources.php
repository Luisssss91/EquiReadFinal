<?php include 'config.php'; ?>
<?php include 'includes/header.php'; ?>

<style>
/* Add/modify these styles for centered modal content */
.modal {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    padding: 20px;
    box-sizing: border-box;
}

.modal-content {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.wide-modal {
    max-width: 95%;
    max-height: 95vh;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #f8f9fa;
    flex-shrink: 0;
}

.modal-header h3 {
    margin: 0;
    color: #333;
    font-size: 1.3rem;
}

.modal-body {
    padding: 20px;
    overflow-y: auto;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    line-height: 1;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.modal-close:hover {
    background-color: #eee;
    color: #333;
}

.modal-actions {
    padding-top: 20px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: auto;
    flex-shrink: 0;
}

/* TTS specific styles */
.extraction-status,
.tts-content-display {
    text-align: center;
    padding: 20px 0;
}

.loading-spinner {
    text-align: center;
    padding: 40px 20px;
}

.loading-spinner i {
    margin-bottom: 15px;
    color: var(--primary-color);
}

.alert {
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    text-align: center;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}

.preview-box {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 15px;
    margin: 15px 0;
    max-height: 200px;
    overflow-y: auto;
    text-align: left;
}

.tts-preview-controls {
    display: flex;
    flex-direction: column;
    gap: 20px;
    align-items: center;
}

.tts-main-controls {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.tts-settings {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    justify-content: center;
}

.tts-preview-speed,
.tts-preview-voice,
.tts-preview-pitch {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 120px;
}

.tts-preview-status {
    text-align: center;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 6px;
    margin: 10px 0;
}

.tts-content-info {
    text-align: center;
    margin-top: 10px;
    color: #666;
}

.extracted-content {
    text-align: center;
    padding: 20px;
}

.content-preview {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.preview-text {
    text-align: left;
    background-color: white;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    max-height: 300px;
    overflow-y: auto;
    margin: 15px 0;
}

.extraction-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    margin-top: 20px;
}

/* File preview specific */
#previewContent {
    flex-grow: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    background-color: #f8f9fa;
    border-radius: 6px;
    overflow: hidden;
}

#previewContent iframe,
#previewContent img,
#previewContent video {
    width: 100%;
    height: 100%;
    min-height: 400px;
    border: none;
}

/* Resource grid adjustments */
.resource-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: center;
}

.resource-actions .btn {
    flex: 1;
    min-width: 120px;
    text-align: center;
}

/* Progress bar */
.progress-bar {
    width: 100%;
    height: 8px;
    background-color: #e9ecef;
    border-radius: 4px;
    margin: 15px 0;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary-color);
    border-radius: 4px;
    transition: width 0.3s ease;
}

/* Full content display */
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
</style>

<div class="main-content">
    <div class="container">
        <h1>Educational Resources</h1>
        
        <!-- Search and Filter Section -->
        <div class="search-filter">
            <form method="GET" class="search-form">
                <div class="form-row">
                    <div class="form-group search-input">
                        <input type="text" name="search" class="form-control" placeholder="Search resources..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <select name="file_type" class="form-control">
                            <option value="">All Types</option>
                            <option value="pdf" <?php echo (isset($_GET['file_type']) && $_GET['file_type'] == 'pdf') ? 'selected' : ''; ?>>PDF Document</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>
                            <?php
                            // Get all categories from database
                            $categories_sql = "SELECT * FROM tbl_categories ORDER BY name";
                            $categories_result = mysqli_query($conn, $categories_sql);
                            
                            if(mysqli_num_rows($categories_result) > 0) {
                                while($category = mysqli_fetch_assoc($categories_result)) {
                                    $selected = (isset($_GET['category']) && $_GET['category'] == $category['name']) ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($category['name']) . "\" $selected>" . htmlspecialchars($category['name']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="resources.php" class="btn btn-outline">Clear Filters</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Resources Grid -->
        <?php
        // Build query based on filters
        $sql = "SELECT r.*, c.color, c.icon 
                FROM tbl_resources r 
                LEFT JOIN tbl_categories c ON r.category = c.name 
                WHERE 1=1";
        $params = [];
        $types = [];
        
        if(isset($_GET['search']) && !empty($_GET['search'])) {
            $sql .= " AND (r.title LIKE ? OR r.description LIKE ?)";
            $search_term = "%" . $_GET['search'] . "%";
            $params[] = $search_term;
            $params[] = $search_term;
            $types[] = 'ss';
        }
        
        if(isset($_GET['file_type']) && !empty($_GET['file_type'])) {
            $sql .= " AND r.file_type = ?";
            $params[] = $_GET['file_type'];
            $types[] = 's';
        }
        
        if(isset($_GET['category']) && !empty($_GET['category'])) {
            $sql .= " AND r.category = ?";
            $params[] = $_GET['category'];
            $types[] = 's';
        }
        
        $sql .= " ORDER BY r.upload_date DESC";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if(!empty($params)) {
            mysqli_stmt_bind_param($stmt, implode('', $types), ...$params);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($result) > 0):
        ?>
        <div class="resource-grid">
            <?php while($row = mysqli_fetch_assoc($result)): 
                $category_color = $row['color'] ?? '#e9e6e7ff';
                $category_icon = $row['icon'] ?? 'fas fa-folder';
                $file_exists = ($row['file_path'] && file_exists($row['file_path']));
                $file_type = strtolower($row['file_type'] ?? '');
                $is_image = in_array($file_type, ['jpg', 'jpeg', 'png', 'gif']);
                $is_pdf = ($file_type == 'pdf');
                $is_video = in_array($file_type, ['mp4', 'avi', 'mov']);
                $is_audio = in_array($file_type, ['mp3', 'wav']);
                $is_document = in_array($file_type, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
            ?>
            <div class="resource-card">
                <div class="resource-header">
                    <span class="resource-type"><?php echo isset($row['file_type']) ? strtoupper(htmlspecialchars($row['file_type'])) : 'General'; ?></span>
                    <?php if(!empty($row['category'])): ?>
                        <span class="resource-category" style="background: <?php echo $category_color; ?>">
                            <i class="<?php echo $category_icon; ?>"></i>
                            <?php echo htmlspecialchars($row['category']); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- File Preview Section -->
                <div class="resource-preview">
                    <?php if($file_exists): ?>
                        <?php if($is_image): ?>
                            <!-- Image Preview -->
                            <div class="file-preview image-preview">
                                <img src="<?php echo htmlspecialchars($row['file_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['title']); ?>"
                                     onclick="openPreviewModal('<?php echo htmlspecialchars($row['file_path']); ?>', 'image')">
                                <div class="preview-overlay" onclick="openPreviewModal('<?php echo htmlspecialchars($row['file_path']); ?>', 'image')">
                                    <i class="fas fa-search-plus"></i>
                                    <span>Click to view image</span>
                                </div>
                            </div>
                        <?php elseif($is_pdf): ?>
                            <!-- PDF Preview -->
                            <div class="file-preview pdf-preview">
                                <div class="pdf-thumbnail" onclick="openPreviewModal('<?php echo htmlspecialchars($row['file_path']); ?>', 'pdf')">
                                    <i class="fas fa-file-pdf"></i>
                                    <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                                    <p>Click to preview PDF</p>
                                </div>
                                <div class="preview-overlay" onclick="openPreviewModal('<?php echo htmlspecialchars($row['file_path']); ?>', 'pdf')">
                                    <i class="fas fa-search"></i>
                                    <span>Preview PDF</span>
                                </div>
                            </div>
                        <?php elseif($is_video): ?>
                            <!-- Video Preview -->
                            <div class="file-preview video-preview">
                                <video controls onclick="openPreviewModal('<?php echo htmlspecialchars($row['file_path']); ?>', 'video')">
                                    <source src="<?php echo htmlspecialchars($row['file_path']); ?>" type="video/<?php echo $file_type; ?>">
                                    Your browser does not support the video tag.
                                </video>
                                <div class="preview-overlay" onclick="openPreviewModal('<?php echo htmlspecialchars($row['file_path']); ?>', 'video')">
                                    <i class="fas fa-play-circle"></i>
                                    <span>Play video</span>
                                </div>
                            </div>
                        <?php elseif($is_audio): ?>
                            <!-- Audio Preview -->
                            <div class="file-preview audio-preview">
                                <div class="audio-player">
                                    <i class="fas fa-music"></i>
                                    <audio controls>
                                        <source src="<?php echo htmlspecialchars($row['file_path']); ?>" type="audio/<?php echo $file_type; ?>">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                        <?php elseif($is_document): ?>
                            <!-- Document Preview -->
                            <div class="file-preview document-preview">
                                <div class="document-thumbnail" onclick="openPreviewModal('<?php echo htmlspecialchars($row['file_path']); ?>', 'document')">
                                    <i class="fas fa-file-<?php echo $file_type == 'doc' || $file_type == 'docx' ? 'word' : 
                                                          ($file_type == 'xls' || $file_type == 'xlsx' ? 'excel' : 'powerpoint'); ?>"></i>
                                    <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                                    <p>Click to download or preview</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Generic File Preview -->
                            <div class="file-preview generic-preview">
                                <i class="fas fa-file"></i>
                                <p><?php echo htmlspecialchars($row['title']); ?></p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- No File Available -->
                        <div class="file-preview no-file">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>File not available</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="resource-content">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p class="resource-description"><?php echo htmlspecialchars($row['description']); ?></p>
                    
                    <div class="resource-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <small>Uploaded: <?php echo date('M j, Y', strtotime($row['upload_date'])); ?></small>
                        </div>
                        <?php if(isset($row['downloads_count']) && $row['downloads_count'] > 0): ?>
                            <div class="meta-item">
                                <i class="fas fa-download"></i>
                                <small><?php echo $row['downloads_count']; ?> downloads</small>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($row['file_size']) && $row['file_size'] > 0): ?>
                            <div class="meta-item">
                                <i class="fas fa-hdd"></i>
                                <small><?php echo formatFileSize($row['file_size']); ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="resource-actions">
                    <a href="resource_view.php?id=<?php echo $row['resource_id']; ?>" class="btn btn-outline">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    <?php if($file_exists): ?>
                        <a href="<?php echo $row['file_path']; ?>" class="btn btn-primary" download onclick="trackDownload(<?php echo $row['resource_id']; ?>)">
                            <i class="fas fa-download"></i> Download
                        </a>
                    <?php endif; ?>
                    <button class="btn btn-tts-preview" onclick="openTTSModal(<?php echo $row['resource_id']; ?>, '<?php echo addslashes($row['title']); ?>', '<?php echo addslashes($row['description']); ?>', '<?php echo $file_type; ?>', '<?php echo htmlspecialchars($row['file_path']); ?>')">
                        <i class="fas fa-volume-up"></i> Listen
                    </button>
                    <?php if($is_pdf && $file_exists): ?>
                        <button class="btn btn-pdf-preview" onclick="openPreviewModal('<?php echo htmlspecialchars($row['file_path']); ?>', 'pdf')">
                            <i class="fas fa-file-pdf"></i> Preview PDF
                        </button>
                    <?php elseif($is_image && $file_exists): ?>
                        <button class="btn btn-image-preview" onclick="openPreviewModal('<?php echo htmlspecialchars($row['file_path']); ?>', 'image')">
                            <i class="fas fa-image"></i> Preview Image
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-search fa-3x"></i>
                <h3>No resources found</h3>
                <p>Try adjusting your search criteria or browse all resources.</p>
            </div>
        <?php endif; ?>
        
        <?php mysqli_stmt_close($stmt); ?>
    </div>
</div>

<!-- TTS Content Extraction Modal -->
<div id="ttsExtractionModal" class="modal">
    <div class="modal-content wide-modal">
        <div class="modal-header">
            <h3 id="ttsExtractionTitle">Extracting Content for Text-to-Speech</h3>
            <button class="modal-close" onclick="closeTTSModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="extraction-status" id="extractionStatus">
                <!-- Content will be loaded here dynamically -->
            </div>
            <div id="ttsContentDisplay" style="display: none;">
                <h4 style="text-align: center;">Extracted Content</h4>
                <div id="extractedContent" class="extracted-content">
                    <!-- Extracted content will appear here -->
                </div>
                <div class="extraction-controls">
                    <div class="form-group">
                        <label for="contentLength">Preview Length:</label>
                        <select id="contentLength" class="form-control-sm">
                            <option value="500">Short (500 characters)</option>
                            <option value="1000" selected>Medium (1000 characters)</option>
                            <option value="2000">Long (2000 characters)</option>
                            <option value="0">Full Content</option>
                        </select>
                    </div>
                    <button class="btn btn-outline" onclick="refreshExtraction()">
                        <i class="fas fa-sync-alt"></i> Refresh Preview
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TTS Preview Modal -->
<div id="ttsPreviewModal" class="modal">
    <div class="modal-content wide-modal">
        <div class="modal-header">
            <h3 id="ttsPreviewTitle">Text-to-Speech Player</h3>
            <button class="modal-close" onclick="closeTTSModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="ttsFileInfo" style="text-align: center;">
                <h4 id="ttsFileName"></h4>
                <p id="ttsFileType"></p>
            </div>
            <div id="ttsPreviewContent" class="full-content-display">
                <!-- TTS content will appear here -->
            </div>
            <div class="tts-preview-controls">
                <div class="tts-main-controls">
                    <button id="previewPlay" class="btn btn-primary">
                        <i class="fas fa-play"></i> Play
                    </button>
                    <button id="previewPause" class="btn btn-outline">
                        <i class="fas fa-pause"></i> Pause
                    </button>
                    <button id="previewStop" class="btn btn-outline">
                        <i class="fas fa-stop"></i> Stop
                    </button>
                    <button id="previewResume" class="btn btn-outline" style="display: none;">
                        <i class="fas fa-redo"></i> Resume from last
                    </button>
                </div>
                <div class="tts-settings">
                    <div class="tts-preview-speed">
                        <label for="previewRate">Speed:</label>
                        <select id="previewRate" class="form-control-sm">
                            <option value="0.5">0.5x</option>
                            <option value="0.75">0.75x</option>
                            <option value="1" selected>Normal</option>
                            <option value="1.25">1.25x</option>
                            <option value="1.5">1.5x</option>
                            <option value="2">2x</option>
                        </select>
                    </div>
                    <div class="tts-preview-voice">
                        <label for="previewVoice">Voice:</label>
                        <select id="previewVoice" class="form-control-sm"></select>
                    </div>
                    <div class="tts-preview-pitch">
                        <label for="previewPitch">Pitch:</label>
                        <select id="previewPitch" class="form-control-sm">
                            <option value="0.5">Very Low</option>
                            <option value="0.75">Low</option>
                            <option value="1" selected>Normal</option>
                            <option value="1.25">High</option>
                            <option value="1.5">Very High</option>
                        </select>
                    </div>
                    <div class="tts-preview-volume">
                        <label for="previewVolume">Volume:</label>
                        <select id="previewVolume" class="form-control-sm">
                            <option value="0.5">Low</option>
                            <option value="0.75">Medium</option>
                            <option value="1" selected>Normal</option>
                            <option value="1.25">High</option>
                            <option value="1.5">Max</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="tts-preview-status" id="previewStatus">
                Ready to play
            </div>
            <div class="tts-content-info">
                <small id="contentStats">Characters: 0 | Words: 0 | Estimated time: 0s</small>
            </div>
            <div class="modal-actions">
                <button class="btn btn-outline" onclick="goBackToExtraction()">
                    <i class="fas fa-arrow-left"></i> Back to Content
                </button>
                <a id="viewFullResource" href="#" class="btn btn-primary">
                    <i class="fas fa-external-link-alt"></i> View Full Resource
                </a>
                <button class="btn btn-outline" onclick="closeTTSModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- File Preview Modal -->
<div id="filePreviewModal" class="modal">
    <div class="modal-content wide-modal">
        <div class="modal-header">
            <h3 id="previewModalTitle">File Preview</h3>
            <button class="modal-close" onclick="closePreviewModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="previewContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-actions">
                <a id="downloadPreviewFile" href="#" class="btn btn-primary" download>
                    <i class="fas fa-download"></i> Download
                </a>
                <button class="btn btn-outline" onclick="closePreviewModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let currentSpeech = null;
let currentUtterance = null;
let speechVoices = [];
let currentExtractedContent = '';
let currentResourceId = 0;
let currentFileType = '';
let currentFilePath = '';
let currentTitle = '';
let currentDescription = '';
let fullPDFContent = '';
let lastCharIndex = 0;
let isPaused = false;
let isPlayingFullContent = false;

// File Preview Modal Functions
function openPreviewModal(filePath, fileType) {
    const modal = document.getElementById('filePreviewModal');
    const previewContent = document.getElementById('previewContent');
    const downloadLink = document.getElementById('downloadPreviewFile');
    const modalTitle = document.getElementById('previewModalTitle');
    
    // Set download link
    downloadLink.href = filePath;
    
    // Clear previous content
    previewContent.innerHTML = '';
    
    // Load content based on file type
    switch(fileType) {
        case 'pdf':
            modalTitle.textContent = 'PDF Preview';
            previewContent.innerHTML = `<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                <iframe src="${filePath}" style="width: 100%; height: 100%; min-height: 500px;"></iframe>
            </div>`;
            break;
        case 'image':
            modalTitle.textContent = 'Image Preview';
            previewContent.innerHTML = `<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; padding: 20px;">
                <img src="${filePath}" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>`;
            break;
        case 'video':
            modalTitle.textContent = 'Video Preview';
            const videoExt = filePath.split('.').pop();
            previewContent.innerHTML = `<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                <video controls autoplay style="max-width: 100%; max-height: 100%;">
                    <source src="${filePath}" type="video/${videoExt}">
                    Your browser does not support the video tag.
                </video>
            </div>`;
            break;
        case 'document':
            modalTitle.textContent = 'Document';
            previewContent.innerHTML = `
                <div style="text-align:center;padding:2rem;width:100%;">
                    <i class="fas fa-file-download fa-4x" style="color:var(--primary-color);margin-bottom:1rem;"></i>
                    <h3>Document Preview</h3>
                    <p>Click download to view this document</p>
                </div>
            `;
            break;
    }
    
    modal.style.display = 'flex';
}

function closePreviewModal() {
    const modal = document.getElementById('filePreviewModal');
    modal.style.display = 'none';
}

// TTS Functions
function openTTSModal(resourceId, title, description, fileType, filePath) {
    currentResourceId = resourceId;
    currentFileType = fileType;
    currentFilePath = filePath;
    currentTitle = title;
    currentDescription = description;
    fullPDFContent = '';
    lastCharIndex = 0;
    isPaused = false;
    isPlayingFullContent = false;
    
    // Show extraction modal first
    const extractionModal = document.getElementById('ttsExtractionModal');
    const extractionTitle = document.getElementById('ttsExtractionTitle');
    
    extractionTitle.textContent = `Preparing to Read: ${title}`;
    
    // Clear previous content
    const extractionStatus = document.getElementById('extractionStatus');
    const ttsContentDisplay = document.getElementById('ttsContentDisplay');
    extractionStatus.style.display = 'block';
    ttsContentDisplay.style.display = 'none';
    extractionStatus.innerHTML = '';
    
    // Remove existing continue button
    const existingBtn = document.getElementById('continueToTTS');
    if (existingBtn) {
        existingBtn.remove();
    }
    
    extractionModal.style.display = 'flex';
    
    // Start extraction based on file type
    if (fileType === 'pdf' && filePath) {
        // Use PDF.js for PDF files
        extractPDFWithPDFJS(filePath, title, description);
    } else {
        // For other file types, use server-side extraction
        extractFileContent(resourceId, title, description);
    }
}

// PDF.js based extraction (client-side, no server dependencies)
function extractPDFWithPDFJS(fileUrl, title, description) {
    const extractionStatus = document.getElementById('extractionStatus');
    extractionStatus.innerHTML = `
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Loading PDF with PDF.js...</p>
            <small>This extracts text directly in your browser</small>
            <div class="progress-bar">
                <div class="progress-fill" id="pdfExtractionProgress" style="width: 0%"></div>
            </div>
        </div>
    `;
    
    // Check if PDF.js is already loaded
    if (typeof pdfjsLib === 'undefined') {
        // Load PDF.js from CDN
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
        
        script.onload = function() {
            // Set worker source
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            startPDFExtraction(fileUrl, title, description);
        };
        
        script.onerror = function() {
            extractionStatus.innerHTML = `
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    Failed to load PDF.js library. Please check your internet connection.
                </div>
                <button class="btn btn-outline" onclick="extractFileContent(${currentResourceId}, '${currentTitle}', '${currentDescription}')">
                    <i class="fas fa-sync-alt"></i> Try Server Extraction
                </button>
            `;
        };
        
        document.head.appendChild(script);
    } else {
        startPDFExtraction(fileUrl, title, description);
    }
}

function startPDFExtraction(fileUrl, title, description) {
    const extractionStatus = document.getElementById('extractionStatus');
    const progressBar = document.getElementById('pdfExtractionProgress');
    
    // Update progress
    if (progressBar) {
        progressBar.style.width = '10%';
    }
    
    // Load the PDF document
    pdfjsLib.getDocument(fileUrl).promise.then(function(pdf) {
        if (progressBar) {
            progressBar.style.width = '30%';
        }
        
        let fullText = `Title: ${title}. Description: ${description}. `;
        let pagePromises = [];
        const totalPages = pdf.numPages;
        
        console.log(`PDF loaded: ${totalPages} pages`);
        
        // Extract text from each page
        for (let pageNum = 1; pageNum <= totalPages; pageNum++) {
            pagePromises.push(
                pdf.getPage(pageNum).then(function(page) {
                    return page.getTextContent().then(function(textContent) {
                        let pageText = '';
                        textContent.items.forEach(function(item) {
                            pageText += item.str + ' ';
                        });
                        
                        // Update progress
                        const progressPercent = 30 + ((pageNum / totalPages) * 60);
                        if (progressBar) {
                            progressBar.style.width = Math.min(progressPercent, 90) + '%';
                        }
                        
                        return {
                            page: pageNum,
                            text: pageText.trim()
                        };
                    });
                })
            );
        }
        
        // Combine all pages
        Promise.all(pagePromises).then(function(pages) {
            // Sort pages by page number
            pages.sort((a, b) => a.page - b.page);
            
            // Combine text
            pages.forEach(function(pageData) {
                if (pageData.text && pageData.text.length > 0) {
                    fullText += `${pageData.text} `;
                }
            });
            
            // Clean up text
            fullText = cleanText(fullText);
            
            // Update progress
            if (progressBar) {
                progressBar.style.width = '100%';
            }
            
            // Update content
            currentExtractedContent = fullText;
            fullPDFContent = fullText;
            
            // Show success
            setTimeout(() => {
                extractionStatus.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Successfully extracted ${totalPages} page${totalPages > 1 ? 's' : ''}
                        (${fullText.length.toLocaleString()} characters)
                    </div>
                    <div style="margin-top: 20px; text-align: center;">
                        <strong>Content Preview:</strong>
                        <div class="preview-box">
                            ${escapeHtml(fullText.substring(0, 500))}${fullText.length > 500 ? '...' : ''}
                        </div>
                    </div>
                `;
                
                // Add continue button
                const continueBtn = document.createElement('button');
                continueBtn.id = 'continueToTTS';
                continueBtn.className = 'btn btn-primary';
                continueBtn.innerHTML = '<i class="fas fa-volume-up"></i> Listen Now';
                continueBtn.onclick = () => openTTSPlayer();
                continueBtn.style.marginTop = '20px';
                continueBtn.style.marginLeft = 'auto';
                continueBtn.style.marginRight = 'auto';
                continueBtn.style.display = 'block';
                
                extractionStatus.appendChild(continueBtn);
                
                // Update stats
                updateContentStats(fullText);
                
            }, 500);
            
        }).catch(function(error) {
            console.error('Error extracting pages:', error);
            extractionStatus.innerHTML = `
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    Error extracting text from pages: ${error.message}
                </div>
                <button class="btn btn-outline" onclick="extractFileContent(${currentResourceId}, '${currentTitle}', '${currentDescription}')">
                    <i class="fas fa-sync-alt"></i> Try Server Extraction
                </button>
            `;
        });
        
    }).catch(function(error) {
        console.error('Error loading PDF:', error);
        extractionStatus.innerHTML = `
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                Error loading PDF: ${error.message}
            </div>
            <div style="margin-top: 10px; text-align: center;">
                <p>This could be because:</p>
                <ul style="text-align: left; margin: 10px 0; display: inline-block;">
                    <li>The PDF is password protected</li>
                    <li>The PDF is corrupted</li>
                    <li>Cross-origin restrictions (try server extraction)</li>
                </ul>
            </div>
            <button class="btn btn-outline" onclick="extractFileContent(${currentResourceId}, '${currentTitle}', '${currentDescription}')">
                <i class="fas fa-sync-alt"></i> Try Server Extraction
            </button>
        `;
    });
}

// Server-side extraction for non-PDF files
function extractFileContent(resourceId, title, description) {
    const extractionStatus = document.getElementById('extractionStatus');
    extractionStatus.innerHTML = `
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Extracting content from server...</p>
            <small>This may take a moment</small>
        </div>
    `;
    
    // Make request to process_document.php
    fetch(`process_document.php?resource_id=${resourceId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        },
        credentials: 'include'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Server error: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Server extraction result:', data);
        
        if (data.success) {
            currentExtractedContent = data.text;
            fullPDFContent = data.text;
            showExtractedContent(data.text, data);
        } else {
            // Fallback to description
            const fallbackContent = `Title: ${title}. Description: ${description}. Note: Could not extract content from file.`;
            currentExtractedContent = fallbackContent;
            fullPDFContent = fallbackContent;
            showExtractedContent(fallbackContent, {fallback: true, method: 'description_fallback'});
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        
        // Final fallback
        const fallbackContent = `Title: ${title}. Description: ${description}. Note: Using description only.`;
        currentExtractedContent = fallbackContent;
        fullPDFContent = fallbackContent;
        showExtractedContent(fallbackContent, {fallback: true, method: 'error_fallback'});
    });
}

function showExtractedContent(content, data) {
    const extractionStatus = document.getElementById('extractionStatus');
    const ttsContentDisplay = document.getElementById('ttsContentDisplay');
    const extractedContent = document.getElementById('extractedContent');
    
    // Hide loading, show content
    extractionStatus.style.display = 'none';
    ttsContentDisplay.style.display = 'block';
    
    // Display content
    let html = `<div class="content-preview">`;
    
    if (data.fallback) {
        html += `<div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    ${data.method === 'error_fallback' ? 'Network error. Using description as fallback.' : 'Using description as fallback.'}
                </div>`;
    } else {
        html += `<div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Content extracted successfully (${data.method || 'unknown method'})
                </div>`;
    }
    
    // Show preview based on selected length
    const contentLength = document.getElementById('contentLength') ? 
        parseInt(document.getElementById('contentLength').value) : 1000;
    
    let preview = content;
    if (contentLength > 0 && content.length > contentLength) {
        preview = content.substring(0, contentLength) + '...';
    }
    
    html += `<div class="preview-text">${escapeHtml(preview).replace(/\n/g, '<br>')}</div>`;
    html += `<small style="display: block; text-align: center;">${content.length.toLocaleString()} characters total</small>`;
    html += `</div>`;
    
    extractedContent.innerHTML = html;
    
    // Add continue button
    const continueBtn = document.createElement('button');
    continueBtn.id = 'continueToTTS';
    continueBtn.className = 'btn btn-primary';
    continueBtn.innerHTML = '<i class="fas fa-volume-up"></i> Start Listening';
    continueBtn.onclick = () => openTTSPlayer();
    continueBtn.style.marginLeft = 'auto';
    continueBtn.style.marginRight = 'auto';
    continueBtn.style.display = 'block';
    
    const controls = document.querySelector('.extraction-controls');
    if (controls) {
        // Remove existing button if present
        const existingBtn = document.getElementById('continueToTTS');
        if (existingBtn) existingBtn.remove();
        
        controls.appendChild(continueBtn);
    }
    
    // Update stats
    updateContentStats(content);
}

function refreshExtraction() {
    const contentLength = document.getElementById('contentLength') ? 
        parseInt(document.getElementById('contentLength').value) : 1000;
    
    const extractedContent = document.getElementById('extractedContent');
    let preview = currentExtractedContent;
    
    if (contentLength > 0 && currentExtractedContent.length > contentLength) {
        preview = currentExtractedContent.substring(0, contentLength) + '...';
    }
    
    const previewText = extractedContent.querySelector('.preview-text');
    if (previewText) {
        previewText.innerHTML = escapeHtml(preview).replace(/\n/g, '<br>');
    }
    
    const charCount = extractedContent.querySelector('small');
    if (charCount) {
        charCount.textContent = `${currentExtractedContent.length.toLocaleString()} characters total`;
    }
}

function openTTSPlayer() {
    // Close extraction modal
    document.getElementById('ttsExtractionModal').style.display = 'none';
    
    // Open TTS player modal
    const ttsModal = document.getElementById('ttsPreviewModal');
    const ttsContent = document.getElementById('ttsPreviewContent');
    const fileName = document.getElementById('ttsFileName');
    const fileType = document.getElementById('ttsFileType');
    const viewLink = document.getElementById('viewFullResource');
    
    // Set modal content
    if (fileName) fileName.textContent = currentTitle;
    if (fileType) fileType.textContent = `File Type: ${currentFileType.toUpperCase()}`;
    if (viewLink) viewLink.href = `resource_view.php?id=${currentResourceId}`;
    
    // Display full content for TTS (no truncation)
    let displayContent = fullPDFContent || currentExtractedContent;
    
    if (ttsContent) {
        ttsContent.textContent = displayContent;
        
        // Scroll to top
        ttsContent.scrollTop = 0;
    }
    
    // Initialize TTS controls with FULL content
    initTTSControls(displayContent);
    
    // Show modal
    if (ttsModal) ttsModal.style.display = 'flex';
}

function initTTSControls(content) {
    // Initialize speech synthesis
    if ('speechSynthesis' in window) {
        currentSpeech = window.speechSynthesis;
        
        // Load voices
        function loadVoices() {
            speechVoices = currentSpeech.getVoices();
            const voiceSelect = document.getElementById('previewVoice');
            if (!voiceSelect) return;
            
            voiceSelect.innerHTML = '';
            
            speechVoices.forEach((voice, i) => {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `${voice.name} (${voice.lang})`;
                voiceSelect.appendChild(option);
            });
            
            // Try to set default English voice
            const defaultVoice = speechVoices.find(v => v.lang.startsWith('en-') && v.default) || 
                                 speechVoices.find(v => v.lang.startsWith('en-')) ||
                                 speechVoices[0];
            if (defaultVoice) {
                voiceSelect.value = speechVoices.indexOf(defaultVoice);
            }
        }
        
        loadVoices();
        
        // Some browsers load voices asynchronously
        if (speechSynthesis.onvoiceschanged !== undefined) {
            speechSynthesis.onvoiceschanged = loadVoices;
        }
        
        // Set up control buttons
        setupTTSButtons(content);
        
        // Update content stats
        updateContentStats(content);
    } else {
        // TTS not supported
        const statusDiv = document.getElementById('previewStatus');
        if (statusDiv) {
            statusDiv.textContent = 'Text-to-speech is not supported in your browser';
        }
        
        const playBtn = document.getElementById('previewPlay');
        if (playBtn) playBtn.disabled = true;
    }
}

function setupTTSButtons(content) {
    const playBtn = document.getElementById('previewPlay');
    const pauseBtn = document.getElementById('previewPause');
    const stopBtn = document.getElementById('previewStop');
    const resumeBtn = document.getElementById('previewResume');
    const rateSelect = document.getElementById('previewRate');
    const voiceSelect = document.getElementById('previewVoice');
    const pitchSelect = document.getElementById('previewPitch');
    const volumeSelect = document.getElementById('previewVolume');
    const statusDiv = document.getElementById('previewStatus');
    
    if (!playBtn || !statusDiv) return;
    
    function speakContent(startFromIndex = 0) {
        if (currentSpeech.speaking) {
            currentSpeech.cancel();
        }
        
        const textToSpeak = content || fullPDFContent || currentExtractedContent;
        if (!textToSpeak || textToSpeak.length === 0) {
            updateStatus('No content available to read');
            return;
        }
        
        // Determine what text to speak
        let textToRead = textToSpeak;
        if (startFromIndex > 0 && startFromIndex < textToSpeak.length) {
            textToRead = textToSpeak.substring(startFromIndex);
            isPlayingFullContent = true;
        }
        
        currentUtterance = new SpeechSynthesisUtterance(textToRead);
        
        // Set voice
        if (voiceSelect && speechVoices.length > 0) {
            const selectedVoiceIndex = parseInt(voiceSelect.value);
            if (!isNaN(selectedVoiceIndex) && speechVoices[selectedVoiceIndex]) {
                currentUtterance.voice = speechVoices[selectedVoiceIndex];
            }
        }
        
        // Set rate
        if (rateSelect) {
            currentUtterance.rate = parseFloat(rateSelect.value) || 1;
        }
        
        // Set pitch
        if (pitchSelect) {
            currentUtterance.pitch = parseFloat(pitchSelect.value) || 1;
        }
        
        // Set volume
        if (volumeSelect) {
            currentUtterance.volume = parseFloat(volumeSelect.value) || 1;
        } else {
            currentUtterance.volume = 1;
        }
        
        // Track where we are in the text
        let currentCharIndex = startFromIndex;
        
        // Event listeners
        currentUtterance.onstart = function() {
            updateStatus('Reading content...');
            if (playBtn) playBtn.disabled = true;
            if (pauseBtn) pauseBtn.disabled = false;
            if (stopBtn) stopBtn.disabled = false;
            if (resumeBtn) resumeBtn.style.display = 'none';
        };
        
        currentUtterance.onend = function() {
            if (startFromIndex > 0) {
                updateStatus('Finished reading from where you left off');
            } else {
                updateStatus('Finished reading');
            }
            if (playBtn) playBtn.disabled = false;
            if (pauseBtn) pauseBtn.disabled = true;
            if (stopBtn) stopBtn.disabled = true;
            lastCharIndex = 0;
            isPlayingFullContent = false;
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
        
        currentSpeech.speak(currentUtterance);
    }
    
    function updateStatus(message) {
        statusDiv.textContent = message;
    }
    
    // Button event listeners
    playBtn.addEventListener('click', function() {
        if (currentSpeech.speaking && currentSpeech.paused) {
            currentSpeech.resume();
            updateStatus('Resumed reading');
            if (playBtn) playBtn.disabled = true;
            if (pauseBtn) pauseBtn.disabled = false;
            if (resumeBtn) resumeBtn.style.display = 'none';
        } else if (!currentSpeech.speaking) {
            speakContent();
        }
    });
    
    if (pauseBtn) {
        pauseBtn.addEventListener('click', function() {
            if (currentSpeech.speaking && !currentSpeech.paused) {
                currentSpeech.pause();
                updateStatus('Paused');
                if (playBtn) playBtn.disabled = false;
                if (pauseBtn) pauseBtn.disabled = true;
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
            currentSpeech.cancel();
            updateStatus('Stopped');
            if (playBtn) playBtn.disabled = false;
            if (pauseBtn) pauseBtn.disabled = true;
            if (resumeBtn) resumeBtn.style.display = 'none';
            lastCharIndex = 0;
            isPlayingFullContent = false;
        });
    }
    
    // Update settings when changed
    if (rateSelect) {
        rateSelect.addEventListener('change', function() {
            if (currentUtterance && currentSpeech.speaking) {
                currentUtterance.rate = parseFloat(this.value);
                currentSpeech.cancel();
                // Resume from where we were
                const resumeFrom = lastCharIndex > 0 ? lastCharIndex : 0;
                speakContent(resumeFrom);
            }
        });
    }
    
    if (voiceSelect) {
        voiceSelect.addEventListener('change', function() {
            if (currentUtterance && currentSpeech.speaking) {
                const selectedVoiceIndex = parseInt(this.value);
                if (!isNaN(selectedVoiceIndex) && speechVoices[selectedVoiceIndex]) {
                    currentUtterance.voice = speechVoices[selectedVoiceIndex];
                    currentSpeech.cancel();
                    // Resume from where we were
                    const resumeFrom = lastCharIndex > 0 ? lastCharIndex : 0;
                    speakContent(resumeFrom);
                }
            }
        });
    }
    
    if (pitchSelect) {
        pitchSelect.addEventListener('change', function() {
            if (currentUtterance && currentSpeech.speaking) {
                currentUtterance.pitch = parseFloat(this.value);
                currentSpeech.cancel();
                // Resume from where we were
                const resumeFrom = lastCharIndex > 0 ? lastCharIndex : 0;
                speakContent(resumeFrom);
            }
        });
    }
    
    if (volumeSelect) {
        volumeSelect.addEventListener('change', function() {
            if (currentUtterance && currentSpeech.speaking) {
                currentUtterance.volume = parseFloat(this.value);
                currentSpeech.cancel();
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
}

function updateContentStats(content) {
    if (!content) return;
    
    const charCount = content.length;
    const wordCount = content.split(/\s+/).filter(word => word.length > 0).length;
    const readingTime = Math.ceil(wordCount / 200); // Average reading speed: 200 words per minute
    
    const statsElement = document.getElementById('contentStats');
    if (statsElement) {
        statsElement.textContent = 
            `Characters: ${charCount.toLocaleString()} | Words: ${wordCount.toLocaleString()} | Estimated time: ${readingTime} min`;
    }
}

function goBackToExtraction() {
    // Close TTS player
    const ttsModal = document.getElementById('ttsPreviewModal');
    if (ttsModal) ttsModal.style.display = 'none';
    
    // Stop any ongoing speech
    if (currentSpeech && currentSpeech.speaking) {
        currentSpeech.cancel();
    }
    
    // Show extraction modal again
    const extractionModal = document.getElementById('ttsExtractionModal');
    if (extractionModal) extractionModal.style.display = 'flex';
}

function closeTTSModal() {
    const extractionModal = document.getElementById('ttsExtractionModal');
    const ttsModal = document.getElementById('ttsPreviewModal');
    
    // Stop any ongoing speech
    if (currentSpeech && currentSpeech.speaking) {
        currentSpeech.cancel();
    }
    
    // Hide modals
    if (extractionModal) extractionModal.style.display = 'none';
    if (ttsModal) ttsModal.style.display = 'none';
}

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const fileModal = document.getElementById('filePreviewModal');
    const extractionModal = document.getElementById('ttsExtractionModal');
    const ttsModal = document.getElementById('ttsPreviewModal');
    
    if (event.target === fileModal) {
        closePreviewModal();
    }
    if (event.target === extractionModal) {
        closeTTSModal();
    }
    if (event.target === ttsModal) {
        closeTTSModal();
    }
});

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

// Utility functions
function cleanText(text) {
    if (!text) return '';
    
    // Remove excessive whitespace
    text = text.replace(/\s+/g, ' ');
    
    // Remove control characters but keep spaces, newlines, and common punctuation
    text = text.replace(/[^\x20-\x7E\x0A\x0D]/g, ' ');
    
    // Trim
    text = text.trim();
    
    return text;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<?php
// Helper function to format file size
function formatFileSize($bytes) {
    if ($bytes == 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}
?>

<?php include 'includes/footer.php'; ?>