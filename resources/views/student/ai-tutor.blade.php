@extends('layouts.student')
@section('title', 'Quizora — AI Tutor')

@push('styles')
<style>
    .ai-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 20px;
        height: calc(100vh - 64px - 56px);
    }

    .ai-sidebar {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        overflow-y: auto;
    }

    .ai-sidebar-title {
        font-size: 11px;
        font-weight: 700;
        color: var(--color-text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }

    .suggestion-chip {
        background: rgba(79, 70, 229, 0.08);
        border: 1px solid rgba(79, 70, 229, 0.2);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 12.5px;
        color: var(--color-text-secondary);
        cursor: pointer;
        transition: all 0.2s;
        line-height: 1.4;
    }

    .suggestion-chip:hover {
        background: rgba(79, 70, 229, 0.18);
        border-color: rgba(129, 140, 248, 0.5);
        color: #fff;
    }

    .ai-sidebar::-webkit-scrollbar {
        width: 3px;
    }

    .ai-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .ai-sidebar::-webkit-scrollbar-thumb {
        background: transparent;
        border-radius: 2px;
    }

    .ai-divider {
        height: 1px;
        background: var(--color-border-light);
        margin: 4px 0;
    }

    .ai-info-box {
        background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.75);
        line-height: 1.6;
    }

    .ai-info-box strong {
        color: #fff;
        display: block;
        margin-bottom: 4px;
        font-size: 12px;
    }

    .ai-chat-wrap {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .chat-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--color-border-light);
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .chat-header-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #4F46E5, #818CF8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #fff;
        flex-shrink: 0;
    }

    .chat-header-info h3 {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
    }

    .chat-header-info p {
        font-size: 12px;
        color: var(--color-text-muted);
        margin-top: 1px;
    }

    .chat-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #34D399;
        margin-left: auto;
        box-shadow: 0 0 6px rgba(52, 211, 153, 0.6);
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 22px;
        display: flex;
        flex-direction: column;
        gap: 18px;
        scroll-behavior: smooth;
    }

    .chat-messages::-webkit-scrollbar {
        width: 4px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: rgba(129, 140, 248, 0.2);
        border-radius: 2px;
    }

    .msg {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        max-width: 82%;
    }

    .msg.user {
        flex-direction: row-reverse;
        margin-left: auto;
    }

    .msg-avatar {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        font-weight: 700;
    }

    .msg.ai .msg-avatar {
        background: linear-gradient(135deg, #4F46E5, #818CF8);
        color: #fff;
    }

    .msg.user .msg-avatar {
        background: rgba(255, 255, 255, 0.08);
        color: var(--color-text-secondary);
        font-size: 13px;
    }

    .msg-bubble {
        padding: 12px 16px;
        border-radius: 14px;
        font-size: 13.5px;
        line-height: 1.65;
    }

    .msg.ai .msg-bubble {
        background: rgba(79, 70, 229, 0.1);
        border: 1px solid rgba(79, 70, 229, 0.2);
        color: var(--color-text-primary);
        border-top-left-radius: 4px;
    }

    .msg.user .msg-bubble {
        background: var(--color-primary-solid);
        color: #fff;
        border-top-right-radius: 4px;
    }

    .msg.ai .msg-bubble strong {
        color: #fff;
    }

    .msg.ai .msg-bubble code {
        background: rgba(0, 0, 0, 0.3);
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 12px;
        font-family: monospace;
    }

    .typing-indicator {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .typing-dots {
        display: flex;
        gap: 4px;
        padding: 12px 16px;
        background: rgba(79, 70, 229, 0.1);
        border: 1px solid rgba(79, 70, 229, 0.2);
        border-radius: 14px;
        border-top-left-radius: 4px;
    }

    .typing-dots span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--color-primary-glow);
        animation: typingBounce 1.2s infinite;
    }

    .typing-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingBounce {

        0%,
        60%,
        100% {
            transform: translateY(0);
            opacity: 0.4;
        }

        30% {
            transform: translateY(-6px);
            opacity: 1;
        }
    }

    .chat-input-bar {
        padding: 16px 20px;
        border-top: 1px solid var(--color-border-light);
        display: flex;
        gap: 10px;
        align-items: flex-end;
        flex-shrink: 0;
    }

    .chat-input-wrap {
        flex: 1;
        background: rgba(255, 255, 255, 0.05);
        border: 1.5px solid var(--color-border-light);
        border-radius: 12px;
        display: flex;
        align-items: flex-end;
        padding: 10px 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .chat-input-wrap:focus-within {
        border-color: rgba(79, 70, 229, 0.6);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .chat-input {
        flex: 1;
        background: none;
        border: none;
        outline: none;
        color: #fff;
        font-size: 14px;
        font-family: var(--font);
        resize: none;
        max-height: 120px;
        line-height: 1.5;
    }

    .chat-input::placeholder {
        color: var(--color-text-muted);
    }

    .send-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--color-primary-solid);
        border: none;
        color: #fff;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        flex-shrink: 0;
    }

    .send-btn:hover {
        background: #4338CA;
        transform: scale(1.05);
    }

    .send-btn:disabled {
        background: rgba(79, 70, 229, 0.3);
        cursor: not-allowed;
        transform: none;
    }

    .welcome-msg {
        margin: auto;
        text-align: center;
        padding: 40px 20px;
        color: var(--color-text-muted);
    }

    .welcome-icon {
        font-size: 52px;
        margin-bottom: 16px;
        display: block;
        color: var(--color-primary-glow);
    }

    .welcome-msg h3 {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
    }

    .welcome-msg p {
        font-size: 13px;
        max-width: 320px;
        margin: 0 auto;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<div class="ai-layout">

    <div class="ai-sidebar">
    <div class="ai-sidebar-title">Suggested Questions</div>
    <div class="suggestion-chip" onclick="sendSuggestion(this)">What are my weakest topics?</div>
    <div class="suggestion-chip" onclick="sendSuggestion(this)">Which quiz did I score best?</div>
    <div class="suggestion-chip" onclick="sendSuggestion(this)">Explain my wrong answers</div>
    <div class="suggestion-chip" onclick="sendSuggestion(this)">Overall performance summary</div>
    <div class="suggestion-chip" onclick="sendSuggestion(this)">What should I revise next?</div>
    <div class="suggestion-chip" onclick="sendSuggestion(this)">Tips to improve my score</div>
    <div class="suggestion-chip" onclick="sendSuggestion(this)">My progress over time</div>

    <div class="ai-divider" style="margin-top: auto;"></div>

    <div class="ai-info-box">
        <strong><i class="ti ti-shield-lock"></i> Your data is private</strong>
        The AI only sees your own quiz history. Nothing is shared with other students.
    </div>
</div>

    <div class="ai-chat-wrap">
        <div class="chat-header">
            <div class="chat-header-avatar">
                <i class="ti ti-brain"></i>
            </div>
            <div class="chat-header-info">
                <h3>Quizora AI Tutor</h3>
                <p>Powered by Llama 3.3 · Knows your full quiz history</p>
            </div>
            <div class="chat-status-dot"></div>
        </div>

        <div class="chat-messages" id="chatMessages">
            <div class="welcome-msg" id="welcomeMsg">
                <i class="ti ti-brain welcome-icon"></i>
                <h3>Hi, {{ auth()->user()->name }}! 👋</h3>
                <p>I know all your quiz attempts, scores, and answers. Ask me anything — I'm here to help you learn and improve.</p>
            </div>
        </div>

        <div class="chat-input-bar">
            <div class="chat-input-wrap">
                <textarea
                    class="chat-input"
                    id="chatInput"
                    rows="1"
                    placeholder="Ask about your results, weak topics, or any question explanation..."></textarea>
            </div>
            <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                <i class="ti ti-send"></i>
            </button>
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

    let history = [];
    let isLoading = false;

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

        fetch("{{ route('student.ai-tutor.chat') }}", {
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