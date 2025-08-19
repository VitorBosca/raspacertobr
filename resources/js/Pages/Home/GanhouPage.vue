<template>
    <div class="winner-page">
        <!-- Header com confetes animados -->
        <div class="confetti-container">
            <div class="confetti" v-for="n in 50" :key="n" :style="getConfettiStyle()"></div>
        </div>

        <!-- Conteúdo principal -->
        <div class="winner-content">
            <!-- Ícone de troféu -->
            <div class="trophy-icon">
                🏆
            </div>

            <!-- Título principal -->
            <h1 class="winner-title">
                🎉 PARABÉNS! 🎉
            </h1>

            <!-- Subtítulo -->
            <h2 class="winner-subtitle">
                Você ganhou uma <span class="highlight">raspadinha grátis!</span>
            </h2>

            <!-- Card com informações do prêmio -->
            <div class="prize-card">
                <div class="prize-icon">🎫</div>
                <div class="prize-info">
                    <h3>Sua Raspadinha Grátis</h3>
                    <p>Confirme para raspar e ganhar prêmios entre</p>
                    <div class="prize-range">
                        <span class="currency">R$</span>
                        <span class="amount">30</span>
                        <span class="separator">a</span>
                        <span class="currency">R$</span>
                        <span class="amount">100</span>
                    </div>
                </div>
            </div>

            <!-- Botões de ação -->
            <div class="action-buttons">
                <button @click="confirmScratch" class="confirm-btn">
                    <span class="btn-icon">🎰</span>
                    RESGATAR RASPADINHA
                </button>
            </div>

            <!-- Informações adicionais -->
            <div class="additional-info">
                <div class="info-item">
                    <span class="info-icon">⏰</span>
                    <span>Válido por 24 horas</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">🎯</span>
                    <span>100% de chance de ganhar</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">💰</span>
                    <span>Saque instantâneo</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="winner-footer">
            <p>🍀 Boa sorte e divirta-se! 🍀</p>
        </div>
    </div>
</template>

<script>

import HttpApi from "@/Services/HttpApi.js";

export default {
    name: 'GanhouPage',
    data() {
        return {
            showAnimation: true
        }
    },
    mounted() {
        // Adiciona som de vitória se disponível
        this.playWinSound();
        
        // Para a animação após alguns segundos
        setTimeout(() => {
            this.showAnimation = false;
        }, 5000);
    },
    methods: {
        confirmScratch() {
            // Redireciona para a página de raspadinha
            console.log('Confirmando raspadinha...');
            // Aqui você pode adicionar a navegação para a página de raspadinha
            // this.$router.push('/raspadinha');
            let gameId = 16;

            HttpApi.post('/scratch-card/create-demo', { gameId })
                .then(response => {
                    // Sucesso ao criar jogo demo
                    console.log('Jogo demo criado:', response.data);
                    this.$router.push('/games/play/' + gameId + '/fortune-tiger'); // Redireciona para a página de jogo
                })
                .catch(error => {
                    // Tratar erro ao criar jogo demo
                    console.error('Erro ao criar jogo demo:', error);
                    alert('Ocorreu um erro ao criar sua raspadinha. Tente novamente mais tarde.');
                });
            
            // Ou emitir evento para o componente pai
            this.$emit('confirm-scratch');
        },
        
        saveLater() {
            // Salva a raspadinha para usar mais tarde
            console.log('Salvando raspadinha para mais tarde...');
            
            // Aqui você pode salvar no localStorage ou backend
            localStorage.setItem('raspadinha_pendente', JSON.stringify({
                timestamp: Date.now(),
                premio_min: 30,
                premio_max: 100,
                expires_at: Date.now() + (24 * 60 * 60 * 1000) // 24 horas
            }));
            
            // Mostrar mensagem de sucesso
            alert('Raspadinha salva! Você pode usar a qualquer momento nas próximas 24 horas.');
            
            // Redirecionar para página inicial
            // this.$router.push('/');
        },
        
        getConfettiStyle() {
            return {
                left: Math.random() * 100 + '%',
                animationDelay: Math.random() * 3 + 's',
                backgroundColor: this.getRandomColor()
            };
        },
        
        getRandomColor() {
            const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#f0932b', '#eb4d4b', '#6c5ce7'];
            return colors[Math.floor(Math.random() * colors.length)];
        },
        
        playWinSound() {
            // Tenta reproduzir som de vitória
            try {
                const audio = new Audio('/sounds/win.mp3');
                audio.volume = 0.3;
                audio.play().catch(() => {
                    // Silenciosamente falha se não conseguir reproduzir
                });
            } catch (error) {
                // Som não disponível
            }
        }
    }
}
</script>

<style scoped>
.winner-page {
    margin-top: 74px;
    min-height: 100vh;
    background: linear-gradient(135deg, #000000 0%, #764ba2 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    overflow: hidden;
}

/* Animação de confetes */
.confetti-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.confetti {
    position: absolute;
    width: 10px;
    height: 10px;
    background: #ff6b6b;
    animation: confetti-fall 3s linear infinite;
}

@keyframes confetti-fall {
    0% {
        transform: translateY(-100vh) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}

/* Conteúdo principal */
.winner-content {
    background: white;
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
    position: relative;
    z-index: 2;
    animation: slideUp 0.8s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.trophy-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.winner-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: #2d3748;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.winner-subtitle {
    font-size: 1.5rem;
    color: #4a5568;
    margin-bottom: 30px;
    line-height: 1.4;
}

.highlight {
    color: #e53e3e;
    font-weight: bold;
    background: linear-gradient(45deg, #28e504 0%, #29e504d7 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Card do prêmio */
.prize-card {
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 10px 20px #00000036;
}

.prize-icon {
    font-size: 3rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.prize-info h3 {
    font-size: 1.3rem;
    font-weight: bold;
    color: #2d3748;
    margin-bottom: 5px;
}

.prize-info p {
    color: #4a5568;
    margin-bottom: 10px;
}

.prize-range {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 1.5rem;
    font-weight: bold;
}

.currency {
    color: #38a169;
}

.amount {
    color: #e53e3e;
    font-size: 2rem;
}

.separator {
    color: #4a5568;
}

/* Botões de ação */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 30px;
}

.confirm-btn, .save-btn {
    padding: 15px 30px;
    border: none;
    border-radius: .5rem;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.confirm-btn {
    background: #28e504;
    color: #171717;
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
}

.confirm-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.6);
}

.save-btn {
    background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
    color: white;
    box-shadow: 0 5px 15px rgba(116, 185, 255, 0.4);
}

.save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(116, 185, 255, 0.6);
}

.btn-icon {
    font-size: 1.2rem;
}

/* Informações adicionais */
.additional-info {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.info-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    color: #4a5568;
    font-size: 0.95rem;
}

.info-icon {
    font-size: 1.2rem;
}

/* Footer */
.winner-footer {
    position: relative;
    z-index: 2;
    margin-top: 20px;
}

.winner-footer p {
    color: white;
    font-size: 1.1rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

/* Responsividade */
@media (max-width: 768px) {
    .winner-content {
        padding: 30px 20px;
        margin: 20px;
    }
    
    .winner-title {
        font-size: 2rem;
    }
    
    .winner-subtitle {
        font-size: 1.2rem;
    }
    
    .prize-card {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .action-buttons {
        gap: 10px;
    }
    
    .confirm-btn, .save-btn {
        padding: 12px 25px;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .winner-content {
        padding: 25px 15px;
    }
    
    .trophy-icon {
        font-size: 3rem;
    }
    
    .winner-title {
        font-size: 1.8rem;
    }
    
    .prize-range {
        font-size: 1.2rem;
    }
    
    .amount {
        font-size: 1.5rem;
    }
}
</style>