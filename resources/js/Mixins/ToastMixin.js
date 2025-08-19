/**
 * ToastMixin - Mixin global para padronizar toasts
 * Fornece métodos de toast consistentes em todos os componentes
 */

import { useToast } from "vue-toastification";

export const ToastMixin = {
    methods: {
        // Getter para acessar a instância do toast
        getToast() {
            if (!this._toastInstance) {
                this._toastInstance = useToast();
            }
            return this._toastInstance;
        },

        // ===== MÉTODOS BÁSICOS =====
        
        showSuccessToast(message, options = {}) {
            const config = {
                position: 'top-right',
                timeout: 4000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                showCloseButtonOnHover: true,
                icon: '✅',
                ...options
            };
            this.getToast().success(message, config);
        },

        showErrorToast(message, options = {}) {
            const config = {
                position: 'top-center',
                timeout: 6000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                showCloseButtonOnHover: true,
                icon: '⚠️',
                ...options
            };
            this.getToast().error(message, config);
        },

        showWarningToast(message, options = {}) {
            const config = {
                position: 'top-center',
                timeout: 4000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                showCloseButtonOnHover: true,
                icon: '⚠️',
                ...options
            };
            this.getToast().warning(message, config);
        },

        showInfoToast(message, options = {}) {
            const config = {
                position: 'top-center',
                timeout: 4000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                showCloseButtonOnHover: true,
                icon: 'ℹ️',
                ...options
            };
            this.getToast().info(message, config);
        },

        // ===== MÉTODOS ESPECÍFICOS PARA JOGOS =====

        showWinToast(message, options = {}) {
            const config = {
                position: 'top-center',
                timeout: 8000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                showCloseButtonOnHover: true,
                icon: '🏆',
                className: 'toast-success-win',
                ...options
            };
            this.getToast().success(message, config);
        },

        showGameWinToast(prize, options = {}) {
            let message = `🎉 Parabéns! Você ganhou: ${prize.name}`;
            
            if (prize.type === 'money') {
                message += ` - Valor: ${prize.value}`;
            } else {
                message += ` (${prize.value})`;
            }
            
            this.showWinToast(message, options);
        },

        showGameLoseToast(message = '😔 Que pena! Tente novamente na próxima!', options = {}) {
            const config = {
                position: 'top-center',
                timeout: 4000,
                icon: '🎯',
                ...options
            };
            this.getToast().info(message, config);
        },

        showGameToast(message, type = 'info', options = {}) {
            const icons = {
                success: '🎮',
                error: '🚫',
                warning: '⚠️',
                info: '🎯'
            };

            const config = {
                position: 'top-center',
                timeout: 4000,
                icon: icons[type] || '🎮',
                ...options
            };
            
            this.getToast()[type](message, config);
        },

        // ===== MÉTODOS PARA OPERAÇÕES FINANCEIRAS =====

        showPurchaseSuccessToast(message, options = {}) {
            const config = {
                position: 'top-right',
                timeout: 4000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                showCloseButtonOnHover: true,
                icon: '🎫',
                ...options
            };
            this.getToast().success(message, config);
        },

        showMoneyToast(message, options = {}) {
            const config = {
                position: 'top-right',
                timeout: 5000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                showCloseButtonOnHover: true,
                icon: '💰',
                ...options
            };
            this.getToast().success(message, config);
        },

        showPurchaseRequiredToast(message = '🎰 Você precisa comprar uma cartela primeiro!', options = {}) {
            this.showWarningToast(message, options);
        },

        showInsufficientBalanceToast(message = '💰 Saldo insuficiente!', options = {}) {
            this.showWarningToast(message, options);
        },

        // ===== MÉTODOS DE CONVENIÊNCIA =====

        showLoadingToast(message, options = {}) {
            const config = {
                position: 'top-center',
                timeout: 3000,
                icon: '⏳',
                hideProgressBar: true,
                ...options
            };
            this.getToast().info(message, config);
        },

        showOperationErrorToast(error, fallbackMessage = 'Erro na operação') {
            let errorMessage = fallbackMessage;
            
            if (error.response && error.response.data) {
                if (error.response.data.message) {
                    errorMessage = error.response.data.message;
                } else if (error.response.data.errors) {
                    // Laravel validation errors
                    const errors = Object.values(error.response.data.errors).flat();
                    errorMessage = errors[0] || fallbackMessage;
                }
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            this.showErrorToast(errorMessage);
        },

        showConnectivityToast(isOnline) {
            if (isOnline) {
                this.showSuccessToast('🌐 Conexão restabelecida!', {
                    timeout: 3000,
                    position: 'bottom-center'
                });
            } else {
                this.showWarningToast('� Conexão perdida! Verificando...', {
                    timeout: 5000,
                    position: 'bottom-center'
                });
            }
        },

        showValidationToast(message = '⚠️ Verifique os dados informados', options = {}) {
            this.showWarningToast(message, options);
        },

        showUnauthorizedToast(message = '🔒 Acesso negado', options = {}) {
            this.showErrorToast(message, {
                timeout: 4000,
                ...options
            });
        },

        showMaintenanceToast(message = '🔧 Sistema em manutenção', options = {}) {
            this.showInfoToast(message, {
                timeout: 8000,
                position: 'top-center',
                ...options
            });
        },

        // ===== MÉTODOS PARA SESSÃO/AUTH =====

        showLoginSuccessToast(username = '') {
            const message = username ? `👋 Bem-vindo, ${username}!` : '👋 Login realizado com sucesso!';
            this.showSuccessToast(message, {
                timeout: 3000,
                position: 'top-center'
            });
        },

        showLogoutToast() {
            this.showInfoToast('👋 Até logo!', {
                timeout: 2000,
                position: 'top-center'
            });
        },

        showSessionExpiredToast() {
            this.showWarningToast('⏰ Sua sessão expirou. Faça login novamente.', {
                timeout: 6000,
                position: 'top-center'
            });
        }
    }
};

export default ToastMixin;
