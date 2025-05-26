<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Atendente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ url_for('static', filename='style.css') }}">
    <style>
        .chat-container {
            max-width: 480px;
            margin: 60px auto 0 auto;
            background: var(--color-tertiary);
            border-radius: var(--border-radius-m);
            box-shadow: 0 8px 32px 0 rgba(92,61,46,0.10);
            padding: 32px 24px 24px 24px;
            display: flex;
            flex-direction: column;
            min-height: 540px;
        }
        .chat-title {
            text-align: center;
            color: var(--color-primary);
            margin-bottom: 18px;
            font-size: var(--font-size-xl);
            letter-spacing: 1px;
            font-family: 'Poppins', 'Arial', sans-serif;
        }
        #chat {
            border-radius: var(--border-radius-s);
            border: 1px solid #e3e8ee;
            background: var(--color-quaternary);
            padding: 18px 12px;
            height: 340px;
            overflow-y: auto;
            margin-bottom: 18px;
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
            border-radius: var(--border-radius-l);
            max-width: 75%;
            font-size: var(--font-size-m);
            line-height: 1.5;
            box-shadow: 0 1px 4px 0 rgba(92,61,46,0.07);
            word-break: break-word;
            font-family: 'Poppins', sans-serif;
        }
        .msg.cliente .bubble {
            background: var(--color-tertiary);
            color: var(--color-primary);
            margin-left: auto;
            border-bottom-right-radius: var(--border-radius-s);
            border-top-right-radius: var(--border-radius-xl);
            border-top-left-radius: var(--border-radius-l);
            border-bottom-left-radius: var(--border-radius-l);
            border: 1.5px solid #eaaec5;
        }
        .msg.bot .bubble {
            background: var(--color-quaternary);
            color: #256029;
            margin-right: auto;
            border-bottom-left-radius: var(--border-radius-s);
            border-top-left-radius: var(--border-radius-xl);
            border-top-right-radius: var(--border-radius-l);
            border-bottom-right-radius: var(--border-radius-l);
            border: 1.5px solid #b6e2c6;
        }
        .msg.atendente .bubble {
            background: #fff3e0;
            color: #b26a00;
            margin-right: auto;
            border: 1.5px solid #ffe0b2;
            border-radius: var(--border-radius-l);
        }
        .msg .label {
            font-size: 12px;
            margin: 0 8px 0 0;
            color: #7b8a99;
            min-width: 60px;
            text-align: right;
        }
        .msg.atendente .label {
            margin: 0 0 0 8px;
            text-align: left;
        }
        .input-row {
            display: flex;
            gap: 10px;
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
            font-family: 'Poppins', sans-serif;
        }
        #mensagem:focus {
            border: 1.5px solid var(--color-primary);
        }
        button {
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
        button:hover {
            background: linear-gradient(90deg, #402020 60%, #d26070 100%);
            color: #fff;
        }
        @media (max-width: 600px) {
            .chat-container { padding: 12px 2vw; }
            #chat { height: 180px; }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-title">Painel do Atendente</div>
        <div id="chat"></div>
        <div class="input-row">
            <input type="text" id="mensagem" placeholder="Digite sua resposta..." autocomplete="off" onkeydown="if(event.key==='Enter'){enviarMensagem();}">
            <button onclick="enviarMensagem()">Enviar</button>
        </div>
    </div>
    <script>
        function atualizarChat() {
            fetch('/mensagens')
                .then(response => response.json())
                .then(data => {
                    const chat = document.getElementById('chat');
                    chat.innerHTML = "";
                    data.forEach(item => {
                        let label = "";
                        let classe = "";
                        if (item.autor === 'cliente') {
                            label = "Cliente";
                            classe = "cliente";
                        } else if (item.autor === 'bot') {
                            label = "Bot";
                            classe = "bot";
                        } else if (item.autor === 'atendente') {
                            label = "Você";
                            classe = "atendente";
                        }
                        chat.innerHTML += `
                            <div class="msg ${classe}">
                                <span class="bubble">${item.mensagem}</span>
                            </div>
                        `;
                    });
                    chat.scrollTop = chat.scrollHeight;
                });
        }

        function enviarMensagem() {
            const mensagem = document.getElementById('mensagem').value;
            if (mensagem.trim() === "") return;
            fetch('/enviar_atendente', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mensagem: mensagem })
            }).then(() => {
                document.getElementById('mensagem').value = "";
                atualizarChat();
            });
        }

        setInterval(atualizarChat, 1000);
        atualizarChat();
    </script>
</body>
</html>