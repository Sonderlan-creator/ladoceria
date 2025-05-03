import json
import os

def carregar_respostas():
    caminho_arquivo = os.path.join(os.path.dirname(__file__), "respostas.json")
    with open(caminho_arquivo, "r", encoding="utf-8") as arquivo:
        return json.load(arquivo)

def buscar_resposta(mensagem, respostas):
    for chave, resposta in respostas.items():
        if chave.lower() in mensagem.lower():
            return resposta
    return "Desculpe, não entendi sua pergunta. Poderia reformular?"

def iniciar_chatbot():
    respostas = carregar_respostas()
    print("Bem-vindo ao chatbot da doceria! Como posso te ajudar?")
    
    while True:
        mensagem = input("> ")
        if mensagem.lower() in ["sair", "tchau"]:
            print("Obrigado por conversar comigo! Até mais!")
            break
        
        resposta = buscar_resposta(mensagem, respostas)
        print(resposta)

if __name__ == "__main__":
    iniciar_chatbot()