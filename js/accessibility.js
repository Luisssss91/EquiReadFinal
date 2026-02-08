document.addEventListener('DOMContentLoaded', function() {
    // High Contrast Toggle
    const highContrastToggle = document.getElementById('highContrastToggle');
    if(highContrastToggle) {
        highContrastToggle.addEventListener('click', function() {
            document.body.classList.toggle('high-contrast');
            // Save preference to session
            const isHighContrast = document.body.classList.contains('high-contrast');
            updateAccessibilityProfile(isHighContrast ? 'high-contrast' : 'normal');
            
            // Add visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }

    // Font Size Controls
    const increaseFont = document.getElementById('increaseFont');
    const decreaseFont = document.getElementById('decreaseFont');
    const resetFont = document.getElementById('resetFont');

    if(increaseFont) {
        increaseFont.addEventListener('click', function() {
            changeFontSize(2);
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }

    if(decreaseFont) {
        decreaseFont.addEventListener('click', function() {
            changeFontSize(-2);
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }

    if(resetFont) {
        resetFont.addEventListener('click', function() {
            resetFontSize();
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }

    // Text to Speech
    const textToSpeechBtn = document.getElementById('textToSpeech');
    if(textToSpeechBtn) {
        textToSpeechBtn.addEventListener('click', function() {
            toggleTextToSpeech();
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }

    // Footer accessibility controls
    const footerHighContrast = document.getElementById('footerHighContrast');
    const footerLargeText = document.getElementById('footerLargeText');
    const footerReadAloud = document.getElementById('footerReadAloud');
    const footerResetAccessibility = document.getElementById('footerResetAccessibility');
    
    if(footerHighContrast) {
        footerHighContrast.addEventListener('click', function(e) {
            e.preventDefault();
            highContrastToggle.click();
        });
    }
    
    if(footerLargeText) {
        footerLargeText.addEventListener('click', function(e) {
            e.preventDefault();
            increaseFont.click();
        });
    }
    
    if(footerReadAloud) {
        footerReadAloud.addEventListener('click', function(e) {
            e.preventDefault();
            textToSpeechBtn.click();
        });
    }

    if(footerResetAccessibility) {
        footerResetAccessibility.addEventListener('click', function(e) {
            e.preventDefault();
            resetAllAccessibility();
        });
    }

    // Initialize accessibility settings from session
    initializeAccessibility();
});

function changeFontSize(delta) {
    const root = document.documentElement;
    const currentSize = parseFloat(getComputedStyle(root).fontSize);
    const newSize = currentSize + delta;
    
    // Limit font size between 12px and 24px
    if(newSize >= 12 && newSize <= 24) {
        root.style.fontSize = newSize + 'px';
        updateAccessibilityProfile('large-text');
        
        // Show visual feedback
        showAccessibilityFeedback(`Font size ${delta > 0 ? 'increased' : 'decreased'}`);
    } else {
        showAccessibilityFeedback(`Font size cannot be ${delta > 0 ? 'increased' : 'decreased'} further`, true);
    }
}

function resetFontSize() {
    document.documentElement.style.fontSize = '16px';
    updateAccessibilityProfile('normal');
    showAccessibilityFeedback('Font size reset to default');
}

function toggleTextToSpeech() {
    if('speechSynthesis' in window) {
        const speech = window.speechSynthesis;
        
        if(speech.speaking) {
            speech.cancel();
            showAccessibilityFeedback('Text-to-speech stopped');
            return;
        }

        // Get main content to read
        const mainContent = document.querySelector('.main-content');
        if(mainContent) {
            const text = mainContent.textContent || mainContent.innerText;
            
            // Clean the text - remove extra whitespace and limit length
            const cleanText = text.replace(/\s+/g, ' ').trim().substring(0, 1000);
            
            if(cleanText.length === 0) {
                showAccessibilityFeedback('No content available to read', true);
                return;
            }
            
            const utterance = new SpeechSynthesisUtterance(cleanText);
            utterance.rate = 0.8;
            utterance.pitch = 1;
            utterance.volume = 0.8;
            
            utterance.onstart = function() {
                showAccessibilityFeedback('Text-to-speech started');
            };
            
            utterance.onend = function() {
                showAccessibilityFeedback('Text-to-speech completed');
            };
            
            utterance.onerror = function(event) {
                showAccessibilityFeedback('Error with text-to-speech', true);
            };
            
            speech.speak(utterance);
        } else {
            showAccessibilityFeedback('No content found to read', true);
        }
    } else {
        showAccessibilityFeedback('Text-to-speech is not supported in your browser', true);
    }
}

function resetAllAccessibility() {
    // Reset high contrast
    document.body.classList.remove('high-contrast');
    
    // Reset font size
    document.documentElement.style.fontSize = '16px';
    
    // Stop text-to-speech if running
    if('speechSynthesis' in window && window.speechSynthesis.speaking) {
        window.speechSynthesis.cancel();
    }
    
    // Update accessibility profile
    updateAccessibilityProfile('normal');
    
    // Show feedback
    showAccessibilityFeedback('All accessibility settings have been reset');
}

function updateAccessibilityProfile(profile) {
    // Send AJAX request to update user's accessibility profile if user is logged in
    const isLoggedIn = typeof userId !== 'undefined' || document.body.classList.contains('logged-in');
    
    if(isLoggedIn) {
        fetch('update_accessibility.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'profile=' + encodeURIComponent(profile)
        })
        .then(response => response.text())
        .then(data => {
            if(data !== 'success') {
                console.error('Failed to update accessibility profile');
            }
        })
        .catch(error => {
            console.error('Error updating accessibility profile:', error);
        });
    }
    
    // Also save to localStorage for guest users
    localStorage.setItem('equiread_accessibility_profile', profile);
}

function initializeAccessibility() {
    // Check if user is logged in and get profile from session
    const isLoggedIn = document.body.classList.contains('logged-in');
    let savedProfile = 'normal';
    
    if(isLoggedIn) {
        // For logged-in users, the profile is set by the server in the body class
        if(document.body.classList.contains('high-contrast')) {
            savedProfile = 'high-contrast';
        } else if(document.body.classList.contains('large-text')) {
            savedProfile = 'large-text';
        }
    } else {
        // For guest users, get from localStorage
        savedProfile = localStorage.getItem('equiread_accessibility_profile') || 'normal';
        
        // Apply saved settings
        if(savedProfile === 'high-contrast') {
            document.body.classList.add('high-contrast');
        } else if(savedProfile === 'large-text') {
            document.body.classList.add('large-text');
            // Also set font size for large text
            document.documentElement.style.fontSize = '20px';
        }
    }
    
    // Initialize any other accessibility features
    initializeKeyboardNavigation();
}

function initializeKeyboardNavigation() {
    // Add keyboard navigation support
    document.addEventListener('keydown', function(e) {
        // Alt + 1 for high contrast
        if(e.altKey && e.key === '1') {
            e.preventDefault();
            const highContrastToggle = document.getElementById('highContrastToggle');
            if(highContrastToggle) highContrastToggle.click();
        }
        
        // Alt + 2 for text-to-speech
        if(e.altKey && e.key === '2') {
            e.preventDefault();
            const textToSpeechBtn = document.getElementById('textToSpeech');
            if(textToSpeechBtn) textToSpeechBtn.click();
        }
        
        // Alt + 0 to reset all
        if(e.altKey && e.key === '0') {
            e.preventDefault();
            resetAllAccessibility();
        }
    });
}

function showAccessibilityFeedback(message, isError = false) {
    // Remove any existing feedback
    const existingFeedback = document.querySelector('.accessibility-feedback');
    if(existingFeedback) {
        existingFeedback.remove();
    }

    // Create feedback element
    const feedback = document.createElement('div');
    feedback.textContent = message;
    feedback.className = 'accessibility-feedback';
    feedback.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${isError ? '#ff6b6b' : 'var(--primary-color, #ff85a2)'};
        color: white;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 10000;
        font-weight: 500;
        transform: translateX(150%);
        transition: transform 0.3s ease;
        max-width: 300px;
        text-align: center;
    `;
    
    document.body.appendChild(feedback);
    
    // Animate in
    setTimeout(() => {
        feedback.style.transform = 'translateX(0)';
    }, 10);
    
    // Animate out and remove
    setTimeout(() => {
        feedback.style.transform = 'translateX(150%)';
        setTimeout(() => {
            if(document.body.contains(feedback)) {
                document.body.removeChild(feedback);
            }
        }, 300);
    }, 3000);
}

// Add CSS for accessibility focus styles
const style = document.createElement('style');
style.textContent = `
    .accessibility-focus-visible :focus {
        outline: 3px solid var(--primary-color) !important;
        outline-offset: 2px !important;
    }
    
    .high-contrast .accessibility-feedback {
        background: #000000 !important;
        border: 2px solid #ff69b4 !important;
        color: #ffffff !important;
    }
    
    /* Improve focus visibility for all interactive elements */
    button:focus,
    a:focus,
    input:focus,
    select:focus,
    textarea:focus {
        outline: 2px solid var(--primary-color) !important;
        outline-offset: 2px !important;
    }
    
    .high-contrast button:focus,
    .high-contrast a:focus,
    .high-contrast input:focus,
    .high-contrast select:focus,
    .high-contrast textarea:focus {
        outline: 3px solid #ff69b4 !important;
        outline-offset: 3px !important;
    }
`;
document.head.appendChild(style);

// Add class for focus visibility
document.addEventListener('keydown', function(e) {
    if(e.key === 'Tab') {
        document.body.classList.add('accessibility-focus-visible');
    }
});

document.addEventListener('mousedown', function() {
    document.body.classList.remove('accessibility-focus-visible');
});