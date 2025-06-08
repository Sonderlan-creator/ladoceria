<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chatbot</title>
    <style>
        :root {
            --color-primary: #5c3d2e;
            --color-secondary: #402020;
            --color-tertiary: #FCE4D8;
            --color-quaternary: #fff0f5;
            --color-accent: orange;
            --font-family: 'Poppins', Arial, sans-serif;
            --border-radius-m: 18px;
            --border-radius-l: 12px;
        }

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background: transparent;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--color-tertiary);
        }

        #chatbox-container {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            border-radius: var(--border-radius-l);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        #chat-mensagens {
            flex: 1;
            padding: 12px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #fff;
        }

        .mensagem {
            display: flex;
            flex-direction: column;
            max-width: 75%;
        }

        .autor {
            font-size: 10px;
            color: var(--color-secondary);
            margin-bottom: 2px;
        }

        .mensagem-cliente {
            align-self: flex-end;
            background-color: var(--color-accent);
            color: white;
            padding: 10px 14px;
            border-radius: var(--border-radius-m);
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
            border-bottom-right-radius: 0;
            box-shadow: 0 2px 6px rgba(255,140,0,0.08);
        }

        .mensagem-bot {
            align-self: flex-start;
            background-color: var(--color-primary);
            color: white;
            padding: 10px 14px;
            border-radius: var(--border-radius-m);
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
            border-bottom-left-radius: 0;
            box-shadow: 0 2px 6px rgba(92,61,46,0.08);
        }

        .mensagem-atendente {
            align-self: flex-start;
            background-color: var(--color-secondary);
            color: white;
            padding: 10px 14px;
            border-radius: var(--border-radius-m);
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
            border-bottom-left-radius: 0;
            box-shadow: 0 2px 6px rgba(64,32,32,0.08);
        }

        #chat-form {
            display: flex;
            border-top: 1px solid #e0cfc2;
            background: var(--color-quaternary);
        }

        #mensagem {
            flex: 1;
            padding: 10px;
            background: #fff;
            color: var(--color-primary);
            border: none;
            outline: none;
            font-size: 15px;
            border-radius: 0 0 0 var(--border-radius-l);
        }

        #enviar {
            padding: 10px 14px;
            background: var(--color-accent);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 0 0 var(--border-radius-l) 0;
            font-size: 18px;
            transition: background 0.2s;
        }
        #enviar:hover {
            background: var(--color-primary);
        }

        #digitando {
            padding: 4px 12px;
            font-size: 12px;
            color: var(--color-primary);
            font-style: italic;
            display: none;
            background: var(--color-tertiary);
        }
    </style>
</head>
<body style="margin:0; padding:0;">
<div id="chatbox-container" style="width:100%;height:100%;">
    <div id="chat-mensagens"></div>
    <div id="digitando">Atendente está digitando...</div>
    <form id="chat-form">
        <input type="text" id="mensagem" placeholder="Digite sua mensagem..." autocomplete="off">
        <button type="submit" id="enviar">⮞</button>
    </form>
</div>
<script>
    // Gere um cliente_id único e salve no localStorage
    let cliente_id = localStorage.getItem('cliente_id');
    if (!cliente_id) {
        cliente_id = 'cli_' + Math.random().toString(36).substr(2, 16);
        localStorage.setItem('cliente_id', cliente_id);
    }

    const API_URL = 'https://1846146f-9016-4618-8024-67702882e445-00-3f4mdtxk897zl.janeway.replit.dev';

    const form = document.getElementById('chat-form');
    const input = document.getElementById('mensagem');
    const mensagensDiv = document.getElementById('chat-mensagens');
    const digitandoDiv = document.getElementById('digitando');

    function renderHistorico(historico) {
        mensagensDiv.innerHTML = '';
        historico.forEach(msg => {
            let classe = '';
            if (msg.autor === 'cliente') classe = 'mensagem mensagem-cliente';
            else if (msg.autor === 'bot') classe = 'mensagem mensagem-bot';
            else classe = 'mensagem mensagem-atendente';
            let autor = msg.autor === 'cliente' ? 'Você' : (msg.autor === 'bot' ? 'Bot' : 'Atendente');
            mensagensDiv.innerHTML += `<div class="${classe}"><span class="autor">${autor}</span>${msg.mensagem}</div>`;
        });
        mensagensDiv.scrollTop = mensagensDiv.scrollHeight;
    }

    function carregarHistorico() {
        fetch(API_URL + '/mensagens?cliente_id=' + encodeURIComponent(cliente_id))
        .then(r => r.json())
        .then(data => {
            if (data.mensagens) renderHistorico(data.mensagens);
        });
    }
    carregarHistorico();
    setInterval(carregarHistorico, 2000);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const mensagem = input.value.trim();
        if (!mensagem) return;
        fetch(API_URL + '/enviar_cliente', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ mensagem: mensagem, cliente_id: cliente_id })
        }).then(() => {
            input.value = '';
            input.focus();
            setTimeout(carregarHistorico, 300); // Atualiza logo após enviar
        });
    });
</script>
</body>
</html>