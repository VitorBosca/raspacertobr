# Sistema de Toasts - Documentação

Este projeto utiliza um sistema de toasts padronizado e moderno baseado no `vue-toastification` com configurações customizadas.

## Como Usar

### Métodos Básicos (disponíveis em todos os componentes via mixin)

```javascript
// Toasts básicos
this.showSuccessToast('Operação realizada com sucesso!');
this.showErrorToast('Ocorreu um erro!');
this.showWarningToast('Atenção: verifique os dados!');
this.showInfoToast('Informação importante');

// Toasts específicos para jogos
this.showWinToast('Parabéns! Você ganhou!');
this.showGameLoseToast(); // Mensagem padrão de derrota
this.showPurchaseSuccessToast('Cartela comprada!');
this.showMoneyToast('Você recebeu R$ 100,00!');

// Toasts de conveniência
this.showPurchaseRequiredToast(); // "Você precisa comprar uma cartela primeiro!"
this.showInsufficientBalanceToast(); // "Saldo insuficiente!"
this.showOperationErrorToast(error); // Extrai mensagem do erro automaticamente
```

### Métodos Avançados com Opções

```javascript
// Com opções customizadas
this.showSuccessToast('Sucesso!', {
    position: 'bottom-right',
    timeout: 2000,
    icon: '🎉'
});

// Toast de jogo com tipo específico
this.showGameToast('Jogo iniciado!', 'success', {
    timeout: 3000
});

// Toast de loading
this.showLoadingToast('Processando...', {
    timeout: 0 // Não desaparece automaticamente
});
```

### Uso Direto do ToastHelper

```javascript
// Em casos onde o mixin não está disponível
this.$toastHelper.success('Mensagem');
this.$toastHelper.error('Erro');
this.$toastHelper.warning('Aviso');
this.$toastHelper.info('Info');
this.$toastHelper.win('Vitória!');
this.$toastHelper.purchase('Compra realizada!');
this.$toastHelper.money('Dinheiro recebido!');
this.$toastHelper.game('Mensagem do jogo', 'success');
this.$toastHelper.loading('Carregando...');
```

## Tipos de Toast e Suas Características

### Success (Sucesso)
- **Cor**: Verde
- **Posição**: Top-right
- **Duração**: 4 segundos
- **Ícone padrão**: ✅

### Error (Erro)
- **Cor**: Vermelho
- **Posição**: Top-center
- **Duração**: 6 segundos
- **Ícone padrão**: ⚠️

### Warning (Aviso)
- **Cor**: Amarelo/Laranja
- **Posição**: Top-center
- **Duração**: 4 segundos
- **Ícone padrão**: ⚠️

### Info (Informação)
- **Cor**: Azul
- **Posição**: Top-center
- **Duração**: 4 segundos
- **Ícone padrão**: ℹ️

### Win (Vitória)
- **Cor**: Dourado com animação
- **Posição**: Top-center
- **Duração**: 8 segundos
- **Ícone padrão**: 🏆
- **Efeito especial**: Animação de pulso

### Purchase (Compra)
- **Cor**: Verde
- **Posição**: Top-right
- **Duração**: 4 segundos
- **Ícone padrão**: 🎫

### Money (Dinheiro)
- **Cor**: Verde
- **Posição**: Top-right
- **Duração**: 5 segundos
- **Ícone padrão**: 💰

### Game (Jogo)
- **Cor**: Varia conforme o tipo
- **Posição**: Top-center
- **Duração**: 4 segundos
- **Ícones**: 🎮, 🚫, ⚠️, 🎯

## Configurações Globais

Todas as configurações estão centralizadas em:
- `resources/js/app.js` - Configuração do vue-toastification
- `resources/js/Utils/ToastHelper.js` - Lógica do helper
- `resources/js/Mixins/ToastMixin.js` - Métodos de conveniência
- `resources/js/index.css` - Estilos CSS customizados

## Responsividade

Os toasts são automaticamente responsivos:
- **Desktop**: Aparecem no canto direito/centro conforme configurado
- **Mobile**: Se ajustam à largura da tela com padding adequado
- **Dark Mode**: Suporte automático ao modo escuro

## Acessibilidade

- Suporte a leitores de tela
- Controle via teclado
- Contraste adequado
- Animações respeitam `prefers-reduced-motion`

## Exemplos Práticos

### Em um componente de jogo:
```javascript
methods: {
    async buyTicket() {
        try {
            this.showLoadingToast('Comprando cartela...');
            const response = await this.purchaseTicket();
            this.showPurchaseSuccessToast(response.message);
        } catch (error) {
            this.showOperationErrorToast(error);
        }
    },

    onGameWin(prize) {
        if (prize.type === 'money') {
            this.showMoneyToast(`Você ganhou ${prize.value}!`);
        } else {
            this.showWinToast(`Parabéns! Você ganhou: ${prize.name}!`);
        }
    },

    onGameLose() {
        this.showGameLoseToast();
    }
}
```

### Para operações de conectividade:
```javascript
mounted() {
    window.addEventListener('online', () => {
        this.showConnectivityToast(true);
    });
    
    window.addEventListener('offline', () => {
        this.showConnectivityToast(false);
    });
}
```
