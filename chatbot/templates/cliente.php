<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chatbot - Cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ url_for('static', filename='style.css') }}">
    <style>

        #janela-chat {
            position: fixed;
            /* bottom: 40px;
            left: 24px; */
            /* width: 370px; */
            max-width: 96vw;
            background: var(--color-tertiary);
            border-radius: var(--border-radius-m);
            /* box-shadow: 0 8px 32px 0 rgba(92,61,46,0.18); */
            flex-direction: column;
            min-height: 480px;
            z-index: 1002;
            animation: fadeInUp 0.25s;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .chatbox-title {
            text-align: center;
            color: var(--color-primary);
            margin: 22px 0 12px 0;
            font-size: var(--font-size-xl);
            letter-spacing: 1px;
            font-family: 'Poppins', 'Arial', sans-serif;
        }
        #mensagens {
            border-radius: var(--border-radius-s);
            border: 1px solid #e3e8ee;
            background: var(--color-quaternary);
            padding: 60px 12px;
            height: 280px;
            overflow-y: auto;
            margin: 0 18px 18px 18px;
            box-shadow: 0 2px 8px 0 rgba(92,61,46,0.07);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .msg {
            display: flex;
            align-items: flex-end;
            margin-bottom: 6px;
        }
        .msg .bubble {
            padding: 10px 16px;
            border-radius: 18px;
            max-width: 75%;
            font-size: var(--font-size-m);
            line-height: 1.5;
            box-shadow: 0 1px 4px 0 rgba(92,61,46,0.07);
            word-break: break-word;
        }
        .msg.cliente .bubble {
            background: #e6f0ff;
            color: #5c3d2e;
            margin-left: auto;
        }
        .msg.bot .bubble {
            background: #eafbe7;
            color: #256029;
            margin-right: auto;
        }
        .msg.atendente .bubble {
            background: #fff3e0;
            color: #b26a00;
            margin-right: auto;
            border: 1px solid #ffe0b2;
        }
        .input-row {
            display: flex;
            gap: 10px;
            margin: 0 18px 18px 18px;
        }
        #mensagem {
            flex: 1;
            padding: 10px 14px;
            border-radius: var(--border-radius-s);
            border: 1px solid #cfd8dc;
            font-size: var(--font-size-m);
            outline: none;
            transition: border 0.2s;
            background: #fff;
        }
        #mensagem:focus {
            border: 1.5px solid var(--color-primary);
        }
        #enviar {
            background: linear-gradient(90deg, #d26070 60%, #fce4d8 100%);
            color: var(--color-primary);
            border: none;
            border-radius: var(--border-radius-s);
            padding: 0 22px;
            font-size: var(--font-size-m);
            font-weight: var(--font-weight-bold);
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px 0 rgba(92,61,46,0.08);
        }
        #enviar:hover {
            background: linear-gradient(90deg, #402020 60%, #d26070 100%);
            color: #fff;
        }
        @media (max-width: 600px) {
            #janela-chat { left: 2vw; width: 96vw; }
            #mensagens { height: 160px; }
        }
    </style>
</head>
<body>
<!-- Apenas a janela do chat -->
<div id="janela-chat">
    <div class="chatbox-title">Chatbot</div>
    <div id="mensagens"></div>
    <form class="input-row" id="chat-form" autocomplete="off">
        <input type="text" id="mensagem" placeholder="Digite sua mensagem..." autocomplete="off">
        <button type="submit" id="enviar">Enviar</button>
    </form>
</div>

<script>
    // const janelaChat = document.getElementById('janela-chat');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('mensagem');
    const mensagensDiv = document.getElementById('mensagens');

    // Sempre exibe o chat ao carregar
    janelaChat.style.display = 'flex';
    setTimeout(() => { input.focus(); }, 200);

    async function atualizarMensagens() {
        const res = await fetch('/mensagens');
        const mensagens = await res.json();
        let html = '';
        mensagens.forEach(msg => {
            let classe = '';
            if (msg.autor === 'cliente') classe = "cliente";
            else if (msg.autor === 'bot') classe = "bot";
            else if (msg.autor === 'atendente') classe = "atendente";
            html += `
                <div class="msg ${classe}">
                    <span class="bubble">${msg.mensagem}</span>
                </div>
            `;
        });
        mensagensDiv.innerHTML = html;
        mensagensDiv.scrollTop = mensagensDiv.scrollHeight;
    }

    form.addEventListener('submit', async e => {
        e.preventDefault();
        const texto = input.value.trim();
        if (texto === '') return;
        await fetch('/enviar_cliente', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ mensagem: texto })
        });
        input.value = '';
        await atualizarMensagens();
    });

    setInterval(atualizarMensagens, 1200);
    window.onload = atualizarMensagens;
</script>
</body>
</html>