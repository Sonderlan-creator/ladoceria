<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Atendente</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #fce4d8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            display: flex;
            gap: 32px;
        }
        .chats-list {
            background: #fff0f5;
            border-radius: 18px;
            box-shadow: 0 2px 12px #0001;
            padding: 24px 18px;
            min-width: 260px;
            max-height: 500px;
            overflow-y: auto;
        }
        .chats-list h3 {
            margin-top: 0;
            color: #5c3d2e;
        }
        .chat-item {
            padding: 10px 8px;
            margin-bottom: 8px;
            border-radius: 10px;
            cursor: pointer;
            background: #fff;
            border: 1.5px solid #e0cfc2;
            transition: background 0.2s, border 0.2s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        .chat-item.selected, .chat-item:hover {
            background: #ffe0b2;
            border: 1.5px solid orange;
        }
        .finalizar-x {
            color: #e53935;
            font-size: 22px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 50%;
            padding: 2px 7px 2px 7px;
            transition: background 0.15s;
            position: relative;
            display: flex;
            align-items: center;
        }
        .finalizar-x:hover {
            background: #ffe0e0;
        }
        .tooltip {
            visibility: hidden;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 4px 10px;
            position: absolute;
            z-index: 1;
            right: 35px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
            white-space: nowrap;
        }
        .finalizar-x:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }
        .chat-area {
            flex: 1;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px #0001;
            padding: 24px 18px;
            display: flex;
            flex-direction: column;
            min-height: 400px;
        }
        #chat-mensagens {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .msg {
            padding: 8px 14px;
            border-radius: 14px;
            max-width: 70%;
            word-break: break-word;
            font-size: 15px;
            box-shadow: 0 2px 8px #0001;
            display: inline-block;
        }
        .msg-cliente { background: orange; color: #fff; align-self: flex-start; }
        .msg-bot { background: #5c3d2e; color: #fff; align-self: flex-start; }
        .msg-atendente { background: #402020; color: #fff; align-self: flex-end; }
        .msg-autor { font-size: 11px; color: #5c3d2e; margin-bottom: 2px; font-weight: 500; opacity: 0.7; }
        .input-area {
            display: flex;
            gap: 10px;
        }
        #mensagem {
            flex: 1;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1.5px solid #e0cfc2;
            font-size: 15px;
            background: #fff;
            color: #5c3d2e;
            outline: none;
        }
        .btn-enviar {
            padding: 12px 22px;
            background: linear-gradient(90deg, orange, #e69500);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-enviar:hover { background: linear-gradient(90deg, #5c3d2e, orange); }
        .no-chat {
            color: #888;
            text-align: center;
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="chats-list">
            <h3>Chats aguardando</h3>
            <div id="lista-chats"></div>
        </div>
        <div class="chat-area">
            <div id="chat-mensagens"></div>
            <form class="input-area" id="form-mensagem" style="display:none;">
                <input type="text" id="mensagem" placeholder="Digite sua resposta..." autocomplete="off">
                <button type="submit" class="btn-enviar">Enviar</button>
            </form>
            <div class="no-chat" id="no-chat">Selecione um chat para responder</div>
        </div>
    </div>
    <script>
        let chats = [];
        let chatSelecionado = null;
        let listaChatsDiv = document.getElementById('lista-chats');
        let chatMensagensDiv = document.getElementById('chat-mensagens');
        let formMensagem = document.getElementById('form-mensagem');
        let mensagemInput = document.getElementById('mensagem');
        let noChatDiv = document.getElementById('no-chat');

        async function carregarChats() {
            const resp = await fetch('/painel_atendente');
            const data = await resp.json();
            chats = data.chats;
            renderizarListaChats();
        }

        function renderizarListaChats() {
            listaChatsDiv.innerHTML = "";
            if (chats.length === 0) {
                listaChatsDiv.innerHTML = "<div style='color:#888;'>Nenhum cliente aguardando</div>";
                chatSelecionado = null;
                chatMensagensDiv.innerHTML = "";
                formMensagem.style.display = "none";
                noChatDiv.style.display = "";
                return;
            }
            chats.forEach(cliente_id => {
                const div = document.createElement('div');
                div.className = "chat-item" + (chatSelecionado === cliente_id ? " selected" : "");
                div.innerHTML = `
                    <span style="flex:1;cursor:pointer;">Cliente: ${cliente_id.substring(0, 8)}...</span>
                    <span class="finalizar-x" title="Finalizar atendimento" onclick="event.stopPropagation(); finalizarAtendimento('${cliente_id}')">
                        &#10006;
                        <span class="tooltip">Finalizar atendimento</span>
                    </span>
                `;
                div.onclick = () => selecionarChat(cliente_id);
                listaChatsDiv.appendChild(div);
            });
        }

        async function selecionarChat(cliente_id) {
            chatSelecionado = cliente_id;
            renderizarListaChats();
            await carregarMensagens();
            formMensagem.style.display = "";
            noChatDiv.style.display = "none";
            mensagemInput.focus();
        }

        async function carregarMensagens() {
            if (!chatSelecionado) return;
            const resp = await fetch('/mensagens_atendente?cliente_id=' + chatSelecionado);
            const data = await resp.json();
            chatMensagensDiv.innerHTML = "";
            data.mensagens.forEach(item => {
                let classe = "msg";
                if (item.autor === 'cliente') classe += " msg-cliente";
                else if (item.autor === 'bot') classe += " msg-bot";
                else if (item.autor === 'atendente') classe += " msg-atendente";
                chatMensagensDiv.innerHTML += `
                    <div>
                        <div class="msg-autor">${item.autor}</div>
                        <div class="${classe}">${item.mensagem}</div>
                    </div>
                `;
            });
            chatMensagensDiv.scrollTop = chatMensagensDiv.scrollHeight;
        }

        formMensagem.onsubmit = async function(e) {
            e.preventDefault();
            const texto = mensagemInput.value.trim();
            if (!texto || !chatSelecionado) return;
            await fetch('/enviar_atendente', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ mensagem: texto, cliente_id: chatSelecionado })
            });
            mensagemInput.value = "";
            await carregarMensagens();
        };

        // Função para finalizar atendimento
        async function finalizarAtendimento(cliente_id) {
            if (confirm("Tem certeza que deseja fazer isso?")) {
                await fetch('/finalizar_atendimento', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ cliente_id })
                });
                if (chatSelecionado === cliente_id) {
                    chatSelecionado = null;
                    chatMensagensDiv.innerHTML = "";
                    formMensagem.style.display = "none";
                    noChatDiv.style.display = "";
                }
                await carregarChats();
            }
        }

        // Atualização automática
        setInterval(() => {
            carregarChats();
            if (chatSelecionado) carregarMensagens();
        }, 1200);

        carregarChats();
    </script>
</body>
</html>