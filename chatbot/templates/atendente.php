<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Atendente</title>
    <style>
        :root {
            --color-primary: #5c3d2e;
            --color-secondary: #402020;
            --color-tertiary: #FCE4D8;
            --color-quaternary: #fff0f5;
            --color-accent: orange;
            --color-accent-dark: #e69500;
            --font-family: 'Poppins', Arial, sans-serif;
            --border-radius-m: 18px;
            --border-radius-l: 32px;
            --shadow: 0 8px 32px rgba(92,61,46,0.18);
        }

        body {
            font-family: var(--font-family);
            background: url('/static/fundo_tela_login.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .painel-container {
            background: rgba(255, 240, 245, 0.93);
            max-width: 540px;
            margin: 48px auto 0 auto;
            border-radius: var(--border-radius-l);
            box-shadow: var(--shadow);
            padding: 32px 28px 24px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            color: var(--color-primary);
            margin-bottom: 18px;
            font-size: 2.2rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #fff0f5;
        }

        #chat {
            border: 1.5px solid #e0cfc2;
            background: #fff;
            padding: 18px 14px;
            height: 320px;
            width: 100%;
            overflow-y: auto;
            border-radius: var(--border-radius-m);
            margin-bottom: 18px;
            box-shadow: 0 4px 16px rgba(92,61,46,0.07);
            font-size: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .msg {
            margin: 0;
            padding: 8px 14px;
            border-radius: var(--border-radius-m);
            max-width: 80%;
            word-break: break-word;
            font-size: 15px;
            box-shadow: 0 2px 8px rgba(92,61,46,0.04);
            display: inline-block;
        }
        .msg-cliente {
            background: var(--color-accent);
            color: #fff;
            align-self: flex-start;
        }
        .msg-bot {
            background: var(--color-primary);
            color: #fff;
            align-self: flex-start;
            opacity: 0.93;
        }
        .msg-atendente {
            background: var(--color-secondary);
            color: #fff;
            align-self: flex-end;
        }
        .msg-autor {
            font-size: 11px;
            color: var(--color-primary);
            margin-bottom: 2px;
            font-weight: 500;
            opacity: 0.7;
        }

        .input-area {
            width: 100%;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        #mensagem {
            flex: 1;
            padding: 12px 14px;
            border-radius: var(--border-radius-m);
            border: 1.5px solid #e0cfc2;
            font-size: 15px;
            background: #fff;
            color: var(--color-primary);
            outline: none;
            transition: border 0.2s;
        }
        #mensagem:focus {
            border: 1.5px solid var(--color-accent);
            background: #fffdfa;
        }

        .btn-enviar {
            padding: 12px 22px;
            background: linear-gradient(90deg, var(--color-accent), var(--color-accent-dark));
            color: white;
            border: none;
            border-radius: var(--border-radius-m);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(255,140,0,0.08);
            transition: background 0.2s, transform 0.1s;
            outline: none;
        }
        .btn-enviar:hover, .btn-enviar:focus {
            background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
            transform: scale(1.04);
        }

        .top-bar {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        .btn-reset {
            background: #fff;
            color: var(--color-accent);
            border: 1.5px solid var(--color-accent);
            border-radius: var(--border-radius-m);
            padding: 7px 18px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            margin-left: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .btn-reset:hover {
            background: var(--color-accent);
            color: #fff;
        }

        @media (max-width: 700px) {
            .painel-container {
                max-width: 98vw;
                padding: 12px 2vw 18px 2vw;
            }
            #chat {
                height: 220px;
            }
        }
    </style>
</head>
<body>
    <div class="painel-container">
        <div class="top-bar">
            <button class="btn-reset" onclick="resetarChat()">Limpar Chat</button>
        </div>
        <h2>Painel do Atendente</h2>
        <div id="chat"></div>
        <form class="input-area" onsubmit="enviarMensagem(); return false;">
            <input type="text" id="mensagem" placeholder="Digite sua resposta..." autocomplete="off" autofocus>
            <button type="submit" class="btn-enviar">Enviar</button>
        </form>
    </div>
    <script>
        let chat = document.getElementById('chat');
        let mensagemInput = document.getElementById('mensagem');

        function renderMensagens(data) {
            chat.innerHTML = "";
            data.forEach(item => {
                let autor = "";
                let classe = "";
                if (item.autor === 'cliente') {
                    autor = "Cliente";
                    classe = "msg msg-cliente";
                } else if (item.autor === 'bot') {
                    autor = "Bot";
                    classe = "msg msg-bot";
                } else if (item.autor === 'atendente') {
                    autor = "Você";
                    classe = "msg msg-atendente";
                }
                chat.innerHTML += `
                    <div>
                        <div class="msg-autor">${autor}</div>
                        <div class="${classe}">${item.mensagem}</div>
                    </div>
                `;
            });
            chat.scrollTop = chat.scrollHeight;
        }

        function atualizarChat() {
            fetch('/mensagens')
                .then(response => response.json())
                .then(data => {
                    renderMensagens(data.mensagens);
                    if (data.modo_atendente) {
                        // Exemplo: destaque visual ou som
                        document.title = "⚠️ Cliente chamou o atendente!";
                        // Ou exiba um banner/alerta na tela
                    } else {
                        document.title = "Painel do Atendente";
                    }
                });
        }

        function enviarMensagem() {
            const mensagem = mensagemInput.value;
            if (mensagem.trim() === "") return;
            fetch('/enviar_atendente', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mensagem: mensagem })
            }).then(() => {
                mensagemInput.value = "";
                atualizarChat();
                mensagemInput.focus();
            });
        }

        function resetarChat() {
            fetch('/resetar')
                .then(() => atualizarChat());
        }

        // Atualização rápida (a cada 600ms)
        setInterval(atualizarChat, 600);
        atualizarChat();

        // Enviar com Enter
        mensagemInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                enviarMensagem();
            }
        });
    </script>
</body>
</html>
