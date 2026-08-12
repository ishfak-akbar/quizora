@extends('layouts.teacher')
@section('title', 'Quizora — AI Assistant')
@section('page-title', 'AI Assistant')
@section('page-subtitle', 'Get AI-powered insights and assistance with your quizzes.')

@push('styles')
<link rel="stylesheet" href="{{ asset('teacher.css') }}">
@endpush

@section('content')
<div class="ai-layout">

    <div class="ai-sidebar">
        <div class="ai-sidebar-title">Suggested Questions</div>
        <div class="suggestion-chip" onclick="sendSuggestion(this)">Student weak topics</div>
        <div class="suggestion-chip" onclick="sendSuggestion(this)">Quiz performance summary</div>
        <div class="suggestion-chip" onclick="sendSuggestion(this)">Lowest pass rate questions</div>
        <div class="suggestion-chip" onclick="sendSuggestion(this)">Improve my hardest quiz</div>
        <div class="suggestion-chip" onclick="sendSuggestion(this)">Generate quiz from document</div>

        <div class="ai-divider" style="margin-top: auto;"></div>

        <div class="ai-info-box">
            <strong><i class="ti ti-shield-lock"></i> Your data stays private</strong>
            The AI only sees your own quizzes, student results, and any file you attach. Nothing is shared with other teachers.
        </div>
    </div>

    <div class="ai-chat-wrap">
        <div class="chat-header">
            <div class="chat-header-avatar"><i class="ti ti-brain"></i></div>
            <div class="chat-header-info">
                <h3>Quizora AI Assistant</h3>
                <p>Powered by Llama 3.3 · Knows your quizzes and student performance</p>
            </div>
            <div class="chat-status-dot"></div>
        </div>

        <div class="chat-messages" id="chatMessages">
            <div class="welcome-msg" id="welcomeMsg">
                <i class="ti ti-brain welcome-icon"></i>
                <h3>Hi, {{ auth()->user()->name }}! 👋</h3>
                <p>I know all your quizzes, questions, and how your students performed. Attach a PDF or text file for extra context, or just ask me anything.</p>
            </div>
        </div>

        <div class="chat-input-bar">
            <div class="attached-file-chip" id="attachedFileChip">
                <i class="ti ti-file-text"></i>
                <span id="attachedFileName"></span>
                <button type="button" onclick="removeAttachedFile()" title="Remove"><i class="ti ti-x"></i></button>
            </div>

            <div class="chat-input-row">
                <input type="file" id="fileInput" accept=".pdf,.txt" style="display:none;" onchange="handleFileSelect(event)">
                <button class="attach-btn" onclick="document.getElementById('fileInput').click()" title="Attach a PDF or text file">
                    <i class="ti ti-paperclip"></i>
                </button>
                <div class="chat-input-wrap">
                    <textarea
                        class="chat-input"
                        id="chatInput"
                        rows="1"
                        placeholder="Ask about your quizzes, student performance, or an attached document..."></textarea>
                </div>
                <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                    <i class="ti ti-send"></i>
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendBtn');
    const userInitial = "{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const attachedFileChip = document.getElementById('attachedFileChip');
    const attachedFileName = document.getElementById('attachedFileName');

    let history = [];
    let isLoading = false;

    @if($uploadedFileName ?? false)
    attachedFileChip.classList.add('visible');
    attachedFileName.textContent = @json($uploadedFileName);
    @endif

    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendSuggestion(el) {
        chatInput.value = el.textContent.trim();
        sendMessage();
    }

    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);

        attachedFileChip.classList.add('visible');
        attachedFileName.textContent = 'Uploading...';

        fetch("{{ route('teacher.ai-assistant.upload') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    attachedFileChip.classList.remove('visible');
                    alert(data.error);
                    return;
                }
                attachedFileName.textContent = data.filename;
            })
            .catch(() => {
                attachedFileChip.classList.remove('visible');
                alert('Failed to upload file.');
            });

        event.target.value = '';
    }

    function removeAttachedFile() {
        fetch("{{ route('teacher.ai-assistant.upload.remove') }}", {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(() => attachedFileChip.classList.remove('visible'));
    }

    function sendMessage() {
        const text = chatInput.value.trim();
        if (!text || isLoading) return;

        const welcome = document.getElementById('welcomeMsg');
        if (welcome) welcome.remove();

        appendMessage('user', text);
        history.push({
            role: 'user',
            content: text
        });

        chatInput.value = '';
        chatInput.style.height = 'auto';

        const typingEl = appendTyping();
        setLoading(true);

        fetch("{{ route('teacher.ai-assistant.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message: text,
                    history: history.slice(0, -1)
                })
            })
            .then(r => r.json())
            .then(data => {
                typingEl.remove();
                setLoading(false);

                if (data.error) {
                    appendMessage('ai', '⚠️ Sorry, something went wrong. Please try again.');
                    return;
                }

                appendMessage('ai', data.reply);
                history.push({
                    role: 'assistant',
                    content: data.reply
                });
            })
            .catch(() => {
                typingEl.remove();
                setLoading(false);
                appendMessage('ai', '⚠️ Could not reach the AI. Please check your connection.');
            });
    }

    function appendMessage(role, text) {
        const isUser = role === 'user';
        const div = document.createElement('div');
        div.className = `msg ${role}`;
        div.innerHTML = `
            <div class="msg-avatar">${isUser ? userInitial : '<i class="ti ti-brain"></i>'}</div>
            <div class="msg-bubble">${formatText(text)}</div>`;
        chatMessages.appendChild(div);
        scrollToBottom();
        return div;
    }

    function appendTyping() {
        const div = document.createElement('div');
        div.className = 'msg ai typing-indicator';
        div.innerHTML = `
            <div class="msg-avatar"><i class="ti ti-brain"></i></div>
            <div class="typing-dots"><span></span><span></span><span></span></div>`;
        chatMessages.appendChild(div);
        scrollToBottom();
        return div;
    }

    function setLoading(state) {
        isLoading = state;
        sendBtn.disabled = state;
        chatInput.disabled = state;
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function formatText(text) {
        return text
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/`(.+?)`/g, '<code>$1</code>')
            .replace(/\n/g, '<br>');
    }
</script>
@endpush