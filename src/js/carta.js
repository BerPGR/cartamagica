import html2canvas from "html2canvas-pro"
import { jsPDF } from 'jspdf'

document.addEventListener('DOMContentLoaded', () => {
    const match = window.location.pathname.match(/\/carta\/(\d+)/);
    const cartaId = match ? match[1] : null;

    const loadingEl = document.getElementById('loading-state');
    const errorEl = document.getElementById('error-state');
    const errorMsgEl = document.getElementById('error-message');
    const pendingEl = document.getElementById('pending-state');
    const pendingTitleEl = document.getElementById('pending-title');
    const pendingMsgEl = document.getElementById('pending-message');
    const pendingBadgeEl = document.getElementById('pending-badge');
    const cardEl = document.getElementById('carta-card');
    const textoEl = document.getElementById('carta-texto');
    const dataEl = document.getElementById('carta-data');
    const rendAreaEl = document.getElementById('carta-render');
    const selectFonteEl = document.getElementById('select-fonte');
    const fundoButtons = document.querySelectorAll('#fundo-swatches button');
    const btnBaixarEl = document.getElementById('btn-baixar-pdf');

    selectFonteEl?.addEventListener('change', (e) => {
        textoEl.className = `whitespace-pre-line leading-relaxed ${e.target.value}`;
    });

    fundoButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            rendAreaEl.className = `rounded-lg p-6 transition-colors ${btn.dataset.bg} ${btn.dataset.text}`;
            fundoButtons.forEach(b => b.classList.remove('ring', 'ring-primary', 'ring-offset-2'));
            btn.classList.add('ring', 'ring-primary', 'ring-offset-2');
        });
    });

    btnBaixarEl?.addEventListener('click', async () => {
        const textoOriginal = btnBaixarEl.textContent;
        btnBaixarEl.disabled = true;
        btnBaixarEl.textContent = 'Gerando PDF...';

        try {
            await document.fonts.ready;

            const canvas = await html2canvas(rendAreaEl, { scale: 2, useCORS: true });
            const imgData = canvas.toDataURL('image/png');

            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'px',
                format: [canvas.width, canvas.height],
            });
            pdf.addImage(imgData, 'PNG', 0, 0, canvas.width, canvas.height);
            pdf.save(`carta-${cartaId}.pdf`);
        } catch (err) {
            console.error(err);
            alert('Não foi possível gerar o PDF. Tente novamente.');
        } finally {
            btnBaixarEl.disabled = false;
            btnBaixarEl.textContent = textoOriginal;
        }
    });


    const STATUS_CONFIG = {
        pago: { liberado: true },
        pending: {
            liberado: false, badge: 'badge-warning', label: 'Pagamento pendente',
            msg: 'Assim que o pagamento for confirmado, sua carta aparece aqui.'
        },
        in_process: {
            liberado: false, badge: 'badge-warning', label: 'Processando pagamento',
            msg: 'Estamos confirmando seu pagamento, aguarde alguns instantes.'
        },
        rejected: {
            liberado: false, badge: 'badge-error', label: 'Pagamento recusado',
            msg: 'O pagamento não foi aprovado. Tente novamente para liberar a carta.'
        },
    };

    if (!cartaId) {
        showError('ID da carta não encontrado na URL.');
        return;
    }

    fetch(`/cartas/${cartaId}`, { headers: { 'Accept': 'application/json' } })
        .then(res => {
            if (!res.ok) throw new Error(`Erro ${res.status} ao buscar a carta.`);
            return res.json();
        })
        .then(data => {
            if (!data || data.error) {
                showError(data?.error || 'Carta não encontrada.');
                return;
            }
            renderPorStatus(data);
        })
        .catch(err => {
            console.error(err);
            showError(err.message || 'Erro ao carregar a carta.');
        });

    function renderPorStatus(data) {
        const config = STATUS_CONFIG[data.status];

        loadingEl.classList.add('hidden');

        if (config?.liberado) {
            textoEl.textContent = data.texto_carta;
            dataEl.textContent = formatarData(data.criado_em);
            cardEl.classList.remove('hidden');
            return;
        }

        pendingTitleEl.textContent = config?.label || 'Status desconhecido';
        pendingMsgEl.textContent = config?.msg || 'Não foi possível determinar o status desta carta.';
        pendingBadgeEl.textContent = data.status;
        pendingBadgeEl.className = `badge badge-lg mt-2 ${config?.badge || 'badge-ghost'}`;
        pendingEl.classList.remove('hidden');
    }

    function formatarData(criadoEm) {
        if (!criadoEm) return '';
        const d = new Date(criadoEm.replace(' ', 'T'));
        if (isNaN(d)) return criadoEm;
        return d.toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' });
    }

    function showError(mensagem) {
        loadingEl.classList.add('hidden');
        errorMsgEl.textContent = mensagem;
        errorEl.classList.remove('hidden');
    }
});