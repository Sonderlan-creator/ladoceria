import React, { useState, useEffect } from 'react';

function Checkout() {
  const [step, setStep] = useState(1);
  const [cart, setCart] = useState([]);
  const [deliveryOption, setDeliveryOption] = useState("retirada");
  const [paymentMethod, setPaymentMethod] = useState("");
  const [orderSummary, setOrderSummary] = useState({});

  useEffect(() => {
    const fetchCart = async () => {
      try {
        const response = await fetch('https://ladoceria.com/cart');
        if (!response.ok) {
          throw new Error('Erro ao buscar o carrinho.');
        }
        const data = await response.json();
        setCart(data);
      } catch (error) {
        console.error('Erro ao buscar o carrinho:', error);
      }
    };

    fetchCart();
  }, []);

  const handleNext = () => setStep(step + 1);
  const handleBack = () => setStep(step - 1);

  const calculateTotal = () => {
    return cart.reduce((total, item) => total + item.price * item.quantity, 0);
  };

  const saveOrder = async (orderData) => {
    try {
      const response = await fetch('https://ladoceria.com/orders', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderData),
      });

      if (!response.ok) {
        throw new Error('Erro ao salvar pedido.');
      }

      const data = await response.json();
      console.log('Pedido salvo:', data);
      return data;
    } catch (error) {
      console.error('Erro ao salvar pedido:', error);
    }
  };

  const handleConfirm = async () => {
    const deliveryDetails = deliveryOption === "delivery" ? {
      name: document.querySelector("input[placeholder='Nome']").value,
      address: document.querySelector("input[placeholder='Endereço']").value,
      houseNumber: document.querySelector("input[placeholder='Número da Casa']").value,
      complement: document.querySelector("input[placeholder='Complemento']").value,
      referencePoint: document.querySelector("input[placeholder='Ponto de Referência']").value,
      phone: document.querySelector("input[placeholder='Telefone']").value,
      observations: document.querySelector("textarea[placeholder='Observações']").value,
    } : null;

    const summary = {
      cart,
      deliveryOption,
      paymentMethod,
      total: calculateTotal(),
    };

    setOrderSummary(summary);
    const result = await saveOrder(summary);

    if (result) {
      alert("Pedido confirmado!");
      console.log(summary);
    } else {
      alert("Ocorreu um erro ao confirmar o pedido.");
    }
  };

  return (
    <div className="p-4 max-w-md mx-auto">
      {step === 1 && (
        <div>
          <h2 className="text-xl font-bold mb-4">Resumo do Carrinho</h2>
          <ul className="mb-4">
            {cart.map((item) => (
              <li key={item.id} className="flex justify-between mb-2">
                <span>{item.name} x{item.quantity}</span>
                <span>R$ {item.price * item.quantity}</span>
              </li>
            ))}
          </ul>
          <div className="text-right font-bold mb-4">Total: R$ {calculateTotal()}</div>
          <button className="btn btn-primary w-full" onClick={handleNext}>Continuar</button>
        </div>
      )}

      {step === 2 && (
        <div>
          <h2 className="text-xl font-bold mb-4">Escolha entre Retirada ou Delivery</h2>
          <div className="flex gap-4 mb-4">
            <button
              className={`btn ${deliveryOption === 'retirada' ? 'btn-primary' : 'btn-secondary'}`}
              onClick={() => setDeliveryOption("retirada")}
            >
              Retirada no Local
            </button>
            <button
              className={`btn ${deliveryOption === 'delivery' ? 'btn-primary' : 'btn-secondary'}`}
              onClick={() => setDeliveryOption("delivery")}
            >
              Delivery
            </button>
          </div>
          {deliveryOption === "delivery" && (
            <div>
              <input
                type="text"
                placeholder="Nome"
                className="input input-bordered w-full mb-4"
                required
              />
              <input
                type="text"
                placeholder="Endereço"
                className="input input-bordered w-full mb-4"
                required
              />
              <input
                type="text"
                placeholder="Número da Casa"
                className="input input-bordered w-full mb-4"
                required
              />
              <input
                type="text"
                placeholder="Complemento"
                className="input input-bordered w-full mb-4"
              />
              <input
                type="text"
                placeholder="Ponto de Referência"
                className="input input-bordered w-full mb-4"
              />
              <input
                type="text"
                placeholder="Telefone"
                className="input input-bordered w-full mb-4"
                required
              />
              <textarea
                placeholder="Observações"
                className="textarea textarea-bordered w-full mb-4"
              ></textarea>
            </div>
          )}
          <div className="flex justify-between">
            <button className="btn btn-secondary" onClick={handleBack}>Voltar</button>
            <button className="btn btn-primary" onClick={handleNext}>Continuar</button>
          </div>
        </div>
      )}

      {step === 3 && (
        <div>
          <h2 className="text-xl font-bold mb-4">Forma de Pagamento</h2>
          <div className="flex gap-4 mb-4">
            <button
              className={`btn ${paymentMethod === 'dinheiro' ? 'btn-primary' : 'btn-secondary'}`}
              onClick={() => setPaymentMethod("dinheiro")}
            >
              Dinheiro
            </button>
            <button
              className={`btn ${paymentMethod === 'pix' ? 'btn-primary' : 'btn-secondary'}`}
              onClick={() => setPaymentMethod("pix")}
            >
              Pix
            </button>
            <button
              className={`btn ${paymentMethod === 'cartao' ? 'btn-primary' : 'btn-secondary'}`}
              onClick={() => setPaymentMethod("cartao")}
            >
              Cartão
            </button>
          </div>
          {paymentMethod === "dinheiro" && (
            <input
              type="text"
              placeholder="Troco para quanto?"
              className="input input-bordered w-full mb-4"
            />
          )}
          <div className="flex justify-between">
            <button className="btn btn-secondary" onClick={handleBack}>Voltar</button>
            <button className="btn btn-primary" onClick={handleNext}>Continuar</button>
          </div>
        </div>
      )}

      {step === 4 && (
        <div>
          <h2 className="text-xl font-bold mb-4">Confirmação</h2>
          <ul className="mb-4">
            {cart.map((item) => (
              <li key={item.id} className="flex justify-between mb-2">
                <span>{item.name} x{item.quantity}</span>
                <span>R$ {item.price * item.quantity}</span>
              </li>
            ))}
          </ul>
          <p className="mb-2">Entrega: {deliveryOption === "retirada" ? "Retirada no Local" : "Delivery"}</p>
          <p className="mb-2">Pagamento: {paymentMethod}</p>
          <div className="text-right font-bold mb-4">Total: R$ {calculateTotal()}</div>
          <button className="btn btn-primary w-full" onClick={handleConfirm}>Confirmar Pedido</button>
        </div>
      )}
    </div>
  );
}

export default Checkout;

