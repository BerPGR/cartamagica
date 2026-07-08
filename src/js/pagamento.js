const mp = new MercadoPago(window.MP_PUBLIC_KEY, { locale: 'pt-BR' });
const bricksBuilder = mp.bricks();

let pollTimer = null;

function mostrarErro(msg) {
  const el = document.getElementById('payment-error');
  el.textContent = msg;
  el.classList.remove('hidden');
}

function esconderBrick() {
  document.getElementById('paymentBrick_container').classList.add('hidden');
}

function mostrarPix(dados) {
  esconderBrick();
  document.getElementById('pix-result').classList.remove('hidden');
  document.getElementById('pix-qr-img').src = 'data:image/png;base64,' + dados.qr_code_base64;

  document.getElementById('pix-copy-btn').addEventListener('click', () => {
    navigator.clipboard.writeText(dados.qr_code);
    document.getElementById('pix-copy-btn').textContent = 'Copiado!';
  });

  iniciarPolling();
}

function mostrarBoleto(dados) {
  esconderBrick();
  document.getElementById('boleto-result').classList.remove('hidden');
  document.getElementById('boleto-link').href = dados.boleto_url;

  iniciarPolling();
}

function iniciarPolling() {
  pollTimer = setInterval(async () => {
    const res = await fetch(`/pagamento/status/${window.CARTA_ID}`);
    const dados = await res.json();
    if (dados.status === 'pago') {
      clearInterval(pollTimer);
      window.location.href = '/cartas';
    }
  }, 4000);
}

async function renderPaymentBrick() {
  const settings = {
    initialization: {
      amount: window.CARTA_VALOR,
    },
    customization: {
      paymentMethods: {
        creditCard: 'all',
        debitCard: 'all',
        bankTransfer: 'all',
        ticket: 'all',       
      },
    },
    callbacks: {
      onReady: () => {},
      onError: (error) => {
        console.error(error);
        mostrarErro('Não foi possível carregar o formulário de pagamento.');
      },
      onSubmit: ({ formData }) => {
        return new Promise((resolve, reject) => {
          fetch('/pagamento/processar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cartaId: window.CARTA_ID, formData }),
          })
            .then((res) => res.json())
            .then((dados) => {
              if (dados.error) {
                mostrarErro(dados.detalhe || dados.error);
                reject();
                return;
              }

              if (dados.payment_method_id === 'pix') {
                mostrarPix(dados);
              } else if (dados.boleto_url) {
                mostrarBoleto(dados);
              } else if (dados.status === 'approved') {
                window.location.href = '/cartas';
              } else {
                mostrarErro('Pagamento não aprovado. Tente outro cartão.');
              }
              resolve();
            })
            .catch(() => {
              mostrarErro('Erro ao processar pagamento.');
              reject();
            });
        });
      },
    },
  };

  window.paymentBrickController = await bricksBuilder.create(
    'payment',
    'paymentBrick_container',
    settings
  );
}

renderPaymentBrick();