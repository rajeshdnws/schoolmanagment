/* Chatbot Widget JavaScript */

(function() {
    'use strict';

    class ChatbotWidget {
        constructor(options = {}) {
            this.apiUrl = options.apiUrl || '/chatbot_api.php';
            this.sessionId = this.generateSessionId();
            this.messages = [];
            this.isOpen = false;
            this.init();
        }

        generateSessionId() {
            return 'session_' + Math.random().toString(36).substr(2, 9);
        }

        init() {
            this.createWidget();
            this.attachEventListeners();
        }

        createWidget() {
            const widget = document.createElement('div');
            widget.className = 'chatbot-widget';
            widget.innerHTML = `
                <button class="chatbot-toggle" title="Chat with us">
                    🤖
                </button>
                <div class="chatbot-container">
                    <div class="chatbot-header">
                        <h3>How can we help?</h3>
                        <button class="chatbot-close">&times;</button>
                    </div>
                    <div class="chatbot-messages">
                        <div class="message bot">
                            <div class="message-content">
                                Hi! 👋 I'm here to help. Feel free to ask me any questions about our school.
                            </div>
                        </div>
                    </div>
                    <div class="chatbot-input-area">
                        <input type="text" class="chatbot-message-input" placeholder="Type your question..." />
                        <button class="chatbot-send-btn">Send</button>
                    </div>
                </div>
            `;

            document.body.appendChild(widget);
            this.widget = widget;
            this.toggle = widget.querySelector('.chatbot-toggle');
            this.container = widget.querySelector('.chatbot-container');
            this.closeBtn = widget.querySelector('.chatbot-close');
            this.messagesArea = widget.querySelector('.chatbot-messages');
            this.input = widget.querySelector('.chatbot-message-input');
            this.sendBtn = widget.querySelector('.chatbot-send-btn');
        }

        attachEventListeners() {
            this.toggle.addEventListener('click', () => this.toggleChat());
            this.closeBtn.addEventListener('click', () => this.closeChat());
            this.sendBtn.addEventListener('click', () => this.sendMessage());
            this.input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') this.sendMessage();
            });
        }

        toggleChat() {
            if (this.isOpen) {
                this.closeChat();
            } else {
                this.openChat();
            }
        }

        openChat() {
            this.isOpen = true;
            this.container.classList.add('active');
            this.toggle.classList.add('active');
            this.input.focus();
        }

        closeChat() {
            this.isOpen = false;
            this.container.classList.remove('active');
            this.toggle.classList.remove('active');
        }

        sendMessage() {
            const message = this.input.value.trim();
            if (!message) return;

            // Add user message to UI
            this.addMessage(message, 'user');
            this.input.value = '';
            this.input.focus();

            // Show typing indicator
            this.showTypingIndicator();

            // Send to API
            this.sendToAPI(message);
        }

        addMessage(text, sender = 'bot', confidence = null) {
            const messageEl = document.createElement('div');
            messageEl.className = `message ${sender}`;
            
            const contentEl = document.createElement('div');
            contentEl.className = 'message-content';
            contentEl.innerHTML = this.escapeHtml(text);
            
            messageEl.appendChild(contentEl);

            // Add confidence indicator
            if (confidence !== null && sender === 'bot') {
                const confidenceEl = document.createElement('div');
                confidenceEl.className = 'message-confidence';
                
                let confidenceClass = 'confidence-low';
                let confidenceText = 'Low confidence';
                
                if (confidence >= 85) {
                    confidenceClass = 'confidence-high';
                    confidenceText = '✓ High match';
                } else if (confidence >= 70) {
                    confidenceClass = 'confidence-medium';
                    confidenceText = '~ Medium match';
                }
                
                confidenceEl.textContent = confidenceText;
                confidenceEl.className += ' ' + confidenceClass;
                messageEl.appendChild(confidenceEl);
            }

            // Add timestamp
            const timeEl = document.createElement('div');
            timeEl.className = 'message-time';
            timeEl.textContent = this.getFormattedTime();
            messageEl.appendChild(timeEl);

            this.messagesArea.appendChild(messageEl);
            this.scrollToBottom();
        }

        showTypingIndicator() {
            const typingEl = document.createElement('div');
            typingEl.className = 'message bot';
            typingEl.innerHTML = `
                <div class="bot-typing">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                </div>
            `;
            typingEl.id = 'typing-indicator';
            this.messagesArea.appendChild(typingEl);
            this.scrollToBottom();
        }

        removeTypingIndicator() {
            const typingEl = document.getElementById('typing-indicator');
            if (typingEl) typingEl.remove();
        }

        sendToAPI(message) {
            const formData = new FormData();
            formData.append('message', message);
            formData.append('session_id', this.sessionId);

            fetch(this.apiUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                this.removeTypingIndicator();
                if (data.success) {
                    this.addMessage(data.message, 'bot', data.confidence);
                    this.sessionId = data.session_id;
                } else {
                    this.addMessage('Sorry, I encountered an error. Please try again.', 'bot');
                }
            })
            .catch(error => {
                console.error('Chatbot error:', error);
                this.removeTypingIndicator();
                this.addMessage('Sorry, I\'m having trouble connecting. Please try again later.', 'bot');
            });
        }

        scrollToBottom() {
            setTimeout(() => {
                this.messagesArea.scrollTop = this.messagesArea.scrollHeight;
            }, 0);
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        getFormattedTime() {
            const now = new Date();
            return now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        }
    }

    // Initialize chatbot when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            new ChatbotWidget();
        });
    } else {
        new ChatbotWidget();
    }
})();
