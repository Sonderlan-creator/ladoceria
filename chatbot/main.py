import nltk
import json
import os
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.model_selection import train_test_split
from fuzzywuzzy import fuzz
from fuzzywuzzy import process
from flask import Flask, render_template, request, jsonify
from datetime import datetime
import mysql.connector
import threading

nltk.download('punkt')
nltk.download('stopwords')
nltk.download('rslp')
nltk.download('punkt_tab')

from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.stem import RSLPStemmer

stop_words = set(stopwords.words("portuguese")) 
stemmer = RSLPStemmer()

def preprocessar(texto):
    tokens = word_tokenize(texto.lower())
    tokens_sem_stopwords = [stemmer.stem(word) for word in tokens if word.isalnum() and word not in stop_words]
    return " ".join(tokens_sem_stopwords)

def carregar_respostas():
    caminho_arquivo = os.path.join(os.path.dirname(__file__), "respostas.json")
    with open(caminho_arquivo, "r", encoding="utf-8") as arquivo:
        return json.load(arquivo)

respostas = carregar_respostas()
dados = []
for chave, valor in respostas.items():
    for pergunta in valor["perguntas"]:
        dados.append((pergunta, chave))

mensagens_processadas = [preprocessar(mensagem) for mensagem, _ in dados]
intenções = [intenção for _, intenção in dados]

vectorizer = CountVectorizer()
X = vectorizer.fit_transform(mensagens_processadas)
y = intenções

if len(dados) > 1:
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    modelo = MultinomialNB()
    modelo.fit(X_train, y_train)
    print(f"Acurácia do modelo: {modelo.score(X_test, y_test) * 100:.2f}%")
else:
    modelo = MultinomialNB()
    modelo.fit(X, y)

def buscar_resposta(mensagem):
    mensagem_processada = preprocessar(mensagem)
    X_msg = vectorizer.transform([mensagem_processada])
    proba = modelo.predict_proba(X_msg)[0]
    idx_max = proba.argmax()
    confianca = proba[idx_max]
    intencao_predita = modelo.classes_[idx_max]

    if confianca > 0.55:
        return respostas[intencao_predita]["resposta"]

    melhor_score = 0
    melhor_intencao = None
    for chave, valor in respostas.items():
        for pergunta in valor["perguntas"]:
            pergunta_processada = preprocessar(pergunta)
            score = fuzz.token_set_ratio(mensagem_processada, pergunta_processada)
            if score > melhor_score:
                melhor_score = score
                melhor_intencao = chave

    if melhor_score >= 75 and melhor_intencao:
        return respostas[melhor_intencao]["resposta"]

    return "Desculpe, não entendi sua pergunta."

def iniciar_chatbot():
    print("Bem-vindo(a) ao chatbot! Como posso te ajudar?")
    while True:
        mensagem = input("> ")
        if mensagem.lower() in ["sair", "tchau", "adeus"]:
            print("Obrigado por conversar comigo! Até logo!")
            break
        resposta = buscar_resposta(mensagem)
        print(resposta)

app = Flask(__name__)

mensagens = []
modo_atendente = False

mensagens.clear()
modo_atendente = False

@app.route('/')
def cliente():
    return render_template('cliente.html')

@app.route('/atendente')
def atendente():
    return render_template('atendente.html')

@app.route('/mensagens')
def listar_mensagens():
    cliente_id = request.args.get('cliente_id')
    if not cliente_id:
        return jsonify({"mensagens": []})
    return jsonify({"mensagens": mensagens_por_cliente.get(cliente_id, [])})

@app.route('/mensagens_atendente')
def mensagens_atendente():
    cliente_id = request.args.get('cliente_id')
    return jsonify({"mensagens": mensagens_por_cliente.get(cliente_id, [])})

@app.route('/enviar_cliente', methods=['POST'])
def enviar_cliente():
    global modo_atendente
    data = request.get_json()
    mensagem = data['mensagem'].strip()
    cliente_id = data.get('cliente_id')
    if not cliente_id:
        return 'Cliente não identificado', 400

    if cliente_id not in mensagens_por_cliente:
        mensagens_por_cliente[cliente_id] = []
    mensagens_por_cliente[cliente_id].append({'autor': 'cliente', 'mensagem': mensagem})

    if cliente_id not in chats_aguardando_atendente:
        resposta = gerar_resposta_chatbot(mensagem)
        mensagens_por_cliente[cliente_id].append({'autor': 'bot', 'mensagem': resposta})

        if "falar com atendente" in mensagem.lower() or mensagem.strip() == "3":
            mensagens_por_cliente[cliente_id].append({'autor': 'bot', 'mensagem': "Aguarde, um atendente irá te responder em instantes."})
            chats_aguardando_atendente.add(cliente_id)

    return '', 204

@app.route('/enviar_atendente', methods=['POST'])
def enviar_atendente():
    data = request.get_json()
    mensagem = data['mensagem'].strip()
    cliente_id = data.get('cliente_id')
    if not cliente_id:
        return 'Cliente não identificado', 400
    if cliente_id not in mensagens_por_cliente:
        mensagens_por_cliente[cliente_id] = []
    mensagens_por_cliente[cliente_id].append({'autor': 'atendente', 'mensagem': mensagem})
    return '', 204

@app.route('/resetar')
def resetar():
    global mensagens, modo_atendente
    mensagens.clear()
    modo_atendente = False
    return "Chat e modo resetados."

def gerar_resposta_chatbot(msg):
    msg = msg.lower()
    hora = datetime.now().hour
    saudacao = "Bom dia" if hora < 12 else "Boa tarde" if hora < 18 else "Boa noite"

    if "verificar pedido" in msg or msg.startswith("pedido"):
        numero = ''.join(filter(str.isdigit, msg))
        if numero:
            return consultar_status_pedido(numero)
        return "Por favor, informe o número do pedido."

    elif "cardápio" in msg or "cardapio" in msg:
        return consultar_cardapio()

    elif "fidelidade" in msg or "pontos" in msg:
        return consultar_fidelidade("cliente_exemplo")  #Lembrete: Trocar pelo identificador real do cliente

    elif msg == "3" or "falar com atendente" in msg:
        global modo_atendente
        modo_atendente = True
        return "Chamando o atendente. Aguarde um momento, por favor."

    resposta_ia = buscar_resposta(msg)
    if resposta_ia != "Desculpe, não entendi sua pergunta.":
        return resposta_ia

def get_db_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="u132528050_ladoceria"
    )

def consultar_status_pedido(numero):
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT * FROM pedidos WHERE id = %s", (numero,))
    pedido = cursor.fetchone()
    cursor.close()
    conn.close()
    if pedido:
        return (
            f"Pedido {pedido['id']}:\n"
            f"Nome: {pedido['nome']}\n"
            f"Tipo: {pedido['tipo']}\n"
            f"Quantidade: {pedido['qtd']}\n"
            f"Observação: {pedido['obs']}\n"
            f"Horário: {pedido['horario']}"
        )
    return "Pedido não encontrado."

def consultar_itens():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT nome, preco, descricao FROM itens")
    itens = cursor.fetchall()
    cursor.close()
    conn.close()
    if itens:
        return "\n".join([f"{item['nome']} - R${item['preco']:.2f}: {item['descricao']}" for item in itens])
    return "Cardápio indisponível no momento."

def consultar_fidelidade(cliente_email):
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT pontos FROM cliente WHERE email = %s", (cliente_email,))
    cliente = cursor.fetchone()
    cursor.close()
    conn.close()
    if cliente:
        return f"Você possui {cliente['pontos']} pontos de fidelidade."
    return "Cliente não encontrado ou sem pontos de fidelidade."

mensagens_por_cliente = {}
chats_aguardando_atendente = set()

@app.route('/painel')
def painel():
    return render_template('painel_atendente.html')

@app.route('/painel_atendente')
def painel_atendente():
    # Retorna lista de cliente_ids aguardando atendimento
    return jsonify({"chats": list(chats_aguardando_atendente)})

@app.route('/finalizar_atendimento', methods=['POST'])
def finalizar_atendimento():
    data = request.get_json()
    cliente_id = data.get('cliente_id')
    if cliente_id in chats_aguardando_atendente:
        chats_aguardando_atendente.remove(cliente_id)
    # Adiciona mensagem de encerramento
    if cliente_id in mensagens_por_cliente:
        mensagens_por_cliente[cliente_id].append({'autor': 'bot', 'mensagem': 'Atendimento encerrado.'})

        # Função para limpar o chat após 10 segundos
        def limpar_chat():
            mensagens_por_cliente.pop(cliente_id, None)
        threading.Timer(10.0, limpar_chat).start()

    return '', 204

if __name__ == '__main__':
    import sys
    if len(sys.argv) > 1 and sys.argv[1] == "web":
        app.run(debug=False)
    else:
        iniciar_chatbot()

janelaChat.style.display = 'flex';