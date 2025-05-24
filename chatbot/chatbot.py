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

nltk.download('punkt')
nltk.download('stopwords')

from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.stem import RSLPStemmer

stop_words = set(stopwords.words("portuguese")) 
stemmer = RSLPStemmer()  # Melhor para português

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

# --- FLASK INTEGRAÇÃO ---
app = Flask(__name__)

mensagens = []
modo_atendente = False

mensagens.clear()
modo_atendente = False

# Simulação de pedidos
pedidos = {
    "1": "Pedido 1: Entregue ✅",
    "2": "Pedido 2: Em trânsito 🚚",
    "3": "Pedido 3: Aguardando pagamento 💳"
}

@app.route('/')
def cliente():
    return render_template('cliente.html')

@app.route('/atendente')
def atendente():
    return render_template('atendente.html')

@app.route('/mensagens')
def listar_mensagens():
    return jsonify(mensagens)

@app.route('/enviar_cliente', methods=['POST'])
def enviar_cliente():
    global modo_atendente
    data = request.get_json()
    mensagem = data['mensagem'].strip()
    mensagens.append({'autor': 'cliente', 'mensagem': mensagem})

    if not modo_atendente:
        resposta = buscar_resposta(mensagem)
        mensagens.append({'autor': 'bot', 'mensagem': resposta})

        if "falar com atendente" in mensagem.lower() or mensagem.strip() == "3":
            mensagens.append({'autor': 'bot', 'mensagem': "Ou, caso deseje, acesse o link: https://wa.me/5511999999999"})
            modo_atendente = True

    return '', 204

@app.route('/enviar_atendente', methods=['POST'])
def enviar_atendente():
    data = request.get_json()
    mensagem = data['mensagem'].strip()
    mensagens.append({'autor': 'atendente', 'mensagem': mensagem})
    return '', 204

@app.route('/resetar')
def resetar():
    global mensagens, modo_atendente
    mensagens.clear()
    modo_atendente = False
    return "Chat e modo resetados."

def buscar_resposta(mensagem):
    # Primeira camada: respostas rápidas de pedidos
    msg = mensagem.lower()
    hora = datetime.now().hour
    saudacao = "Bom dia" if hora < 12 else "Boa tarde" if hora < 18 else "Boa noite"

    if msg in pedidos:
        return pedidos[msg]

    if msg == "1" or "verificar pedido" in msg:
        return "Por favor, digite o número do seu pedido (ex: 1, 2 ou 3)."
    elif msg == "2" or "cancelar pedido" in msg:
        return "Por favor, informe o número do pedido que deseja cancelar."
    elif msg == "3" or "falar com atendente" in msg:
        return "Encaminhando para um atendente..."
    elif msg == "4" or "alterar endereço" in msg:
        return "Por favor, informe o novo endereço de entrega."
    elif msg == "5" or "solicitar reembolso" in msg:
        return "Informe o número do pedido para solicitar o reembolso."
    elif msg == "6" or "horário de funcionamento" in msg or "horario" in msg:
        return "Nosso horário de funcionamento é de segunda a sexta, das 9h às 18h."

    # Segunda camada: NLP
    mensagem_processada = preprocessar(mensagem)
    melhor_score = 0
    melhor_intencao = None

    for chave, valor in respostas.items():
        for pergunta in valor["perguntas"]:
            pergunta_processada = preprocessar(pergunta)
            score = fuzz.ratio(mensagem_processada, pergunta_processada)
            if score > melhor_score:
                melhor_score = score
                melhor_intencao = chave

    if melhor_score >= 60 and melhor_intencao:
        return respostas[melhor_intencao]["resposta"]
    else:
        return (
            f"{saudacao}, em que posso ajudar?\n\n"
            "1. Verificar pedido\n"
            "2. Cancelar pedido\n"
            "3. Falar com atendente\n"
            "4. Alterar endereço de entrega\n"
            "5. Solicitar reembolso\n"
            "6. Horário de funcionamento"
        )

if __name__ == "__main__":
    app.run(debug=False)