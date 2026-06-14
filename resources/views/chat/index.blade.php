@extends('layouts.app')

@section('header')
    AI Assistant Perpustakaan
@endsection

@section('content')
<div class="row">
    <!-- Sidebar Riwayat Chat -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-history mr-1"></i> Riwayat Chat</h3>
                @if($messages->isNotEmpty())
                <form action="{{ route('chat.clear') }}" method="POST"
                    onsubmit="return confirm('Yakin hapus semua riwayat chat?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-xs">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
                @endif
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills nav-sidebar flex-column p-2">
                    @forelse($messages as $msg)
                    <li class="nav-item mb-1">
                        <a href="#" class="nav-link text-sm text-truncate" style="max-width:200px;">
                            {{ $msg->prompt }}
                        </a>
                    </li>
                    @empty
                    <li class="nav-item p-2">
                        <span class="text-muted text-sm">Belum ada riwayat chat.</span>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Area Chat -->
    <div class="col-md-9">
        <div class="card" style="height: 75vh; display: flex; flex-direction: column;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-robot mr-1 text-primary"></i>
                    AI Assistant BukuKita
                </h3>
                <span class="badge badge-success float-right mt-1">Online</span>
            </div>

            <!-- Area Pesan -->
            <div class="card-body" id="chat-container" style="flex:1; overflow-y:auto; background:#f4f6f9;">
                @if($messages->isEmpty())
                <div id="welcome-screen" class="text-center mt-5">
                    <i class="fas fa-robot fa-4x text-primary mb-3"></i>
                    <h4>Halo! Saya AI Assistant BukuKita 👋</h4>
                    <p class="text-muted">Tanyakan apa saja seputar buku dan perpustakaan!</p>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="info-box bg-light" onclick="setPrompt('Buku apa saja yang tersedia?')" style="cursor:pointer;">
                                <span class="info-box-icon"><i class="fas fa-book text-primary"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cek buku tersedia</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-light" onclick="setPrompt('Rekomendasikan buku yang bagus untuk dibaca')" style="cursor:pointer;">
                                <span class="info-box-icon"><i class="fas fa-star text-warning"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Rekomendasi buku</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-light" onclick="setPrompt('Bagaimana cara meminjam buku?')" style="cursor:pointer;">
                                <span class="info-box-icon"><i class="fas fa-question text-success"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cara meminjam buku</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    @foreach($messages->reverse() as $msg)
                    <div class="d-flex justify-content-end mb-3">
                        <div class="bg-primary text-white p-3 rounded" style="max-width:70%; border-radius:15px 15px 0 15px !important;">
                            {{ $msg->prompt }}
                        </div>
                    </div>
                    <div class="d-flex justify-content-start mb-3">
                        <div class="bg-white p-3 rounded shadow-sm" style="max-width:70%; border-radius:15px 15px 15px 0 !important;">
                            <small class="text-primary font-weight-bold"><i class="fas fa-robot mr-1"></i>AI Assistant</small>
                            <p class="mb-0 mt-1">{!! nl2br(e($msg->response)) !!}</p>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

            <!-- Input Chat -->
            <div class="card-footer">
                <div class="input-group">
                    <input type="text" id="user-input" class="form-control"
                        placeholder="Tanyakan sesuatu tentang buku...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="send-btn">
                            <i class="fas fa-paper-plane"></i> Kirim
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
const sendBtn       = document.getElementById('send-btn');
const userInput     = document.getElementById('user-input');
const chatContainer = document.getElementById('chat-container');

sendBtn.addEventListener('click', sendMessage);
userInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') sendMessage();
});

function setPrompt(text) {
    userInput.value = text;
    userInput.focus();
}

function sendMessage() {
    const prompt = userInput.value.trim();
    if (!prompt) return;

    const welcome = document.getElementById('welcome-screen');
    if (welcome) welcome.remove();

    chatContainer.innerHTML += `
        <div class="d-flex justify-content-end mb-3">
            <div class="bg-primary text-white p-3 rounded" style="max-width:70%; border-radius:15px 15px 0 15px !important;">
                ${escapeHtml(prompt)}
            </div>
        </div>`;

    const aiId = 'ai-' + Date.now();
    chatContainer.innerHTML += `
        <div class="d-flex justify-content-start mb-3" id="${aiId}">
            <div class="bg-white p-3 rounded shadow-sm" style="max-width:70%; border-radius:15px 15px 15px 0 !important;">
                <small class="text-primary font-weight-bold"><i class="fas fa-robot mr-1"></i>AI Assistant</small>
                <p class="mb-0 mt-1 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i>Sedang berpikir...</p>
            </div>
        </div>`;

    chatContainer.scrollTop = chatContainer.scrollHeight;
    userInput.value = '';
    sendBtn.disabled = true;

    fetch('{{ route("chat.send") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ prompt: prompt })
    })
    .then(res => res.json())
    .then(data => {
        const aiDiv = document.getElementById(aiId);
        if (data.error) {
            aiDiv.querySelector('p').innerHTML = `<span class="text-danger">${data.error}</span>`;
            return;
        }

        aiDiv.querySelector('p').innerHTML = '';
        let index = 0;
        const p = aiDiv.querySelector('p');
        function typeWriter() {
            if (index < data.text.length) {
                let char = data.text.charAt(index);
                p.innerHTML += char === '\n' ? '<br>' : char;
                index++;
                chatContainer.scrollTop = chatContainer.scrollHeight;
                setTimeout(typeWriter, 10);
            }
        }
        typeWriter();
    })
    .catch(() => {
        document.getElementById(aiId).querySelector('p').innerHTML =
            '<span class="text-danger">Gagal memuat respons.</span>';
    })
    .finally(() => {
        sendBtn.disabled = false;
    });
}

function escapeHtml(text) {
    return text.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
}
</script>
@endpush