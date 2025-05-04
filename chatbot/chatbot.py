import nltk
import json
import os
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.model_selection import train_test_split

nltk.download('punkt')
nltk.download('stopwords')
nltk.download('punkt_tab')

from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.stem import PorterStemmer

stop_words = set(stopwords.words("portuguese")) 
stemmer = PorterStemmer()

def preprocessar(texto):
    tokens = word_tokenize(texto.lower()) 
    tokens_sem_stopwords = [stemmer.stem(word) for word in tokens if word.isalnum() and word not in stop_words]
    return " ".join(tokens_sem_stopwords)

def carregar_respostas():
    caminho_arquivo = os.path.join(os.path.dirname(__file__), "respostas.json")
    with open(caminho_arquivo, "r", encoding="utf-8") as arquivo:
        return json.load(arquivo)

dados = [
    ("Qual é o horário de funcionamento?", "horario"),
    ("Vocês abrem aos domingos?", "horario"),
    ("Onde fica a loja?", "localizacao"),
    ("Qual é o endereço da loja?", "localizacao"),
    ("Vocês têm bolos de aniversário?", "produtos"),
    ("Quais produtos vocês oferecem?", "produtos"),
]

mensagens_processadas = [preprocessar(mensagem) for mensagem, _ in dados]
intenções = [intenção for _, intenção in dados]

vectorizer = CountVectorizer()
X = vectorizer.fit_transform(mensagens_processadas)
y = intenções

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
modelo = MultinomialNB()
modelo.fit(X_train, y_train)

print(f"Acurácia do modelo: {modelo.score(X_test, y_test) * 100:.2f}%")

def buscar_resposta(mensagem):
    mensagem_processada = preprocessar(mensagem)
    mensagem_vetorizada = vectorizer.transform([mensagem_processada])
    previsao = modelo.predict(mensagem_vetorizada)[0]

    respostas = carregar_respostas()

    return respostas.get(previsao, "Desculpe, não entendi sua pergunta.")

def iniciar_chatbot():
    print("Bem-vindo(a) ao chatbot! Como posso te ajudar?")
    
    while True:
        mensagem = input("> ")
        if mensagem.lower() in ["sair", "tchau", "adeus"]:
            print("Obrigado por conversar comigo! Até logo!")
            break
        
        resposta = buscar_resposta(mensagem)
        print(resposta)

if __name__ == "__main__":
    iniciar_chatbot()
