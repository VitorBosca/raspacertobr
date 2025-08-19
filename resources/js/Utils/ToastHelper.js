/**
 * ToastHelper - Utilitário para toasts padronizados
 * Centraliza todas as configurações e tipos de toast do sistema
 */

export class ToastHelper {
    constructor(toast) {
        this.toast = toast;
    }

    // Configurações base para diferentes tipos
    static getBaseConfig() {
        return {
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            showCloseButtonOnHover: true,
        };
    }

    // Toast de sucesso padrão
    success(message, options = {}) {
        const config = {
            ...ToastHelper.getBaseConfig(),
            position: 'top-right',
            timeout: 4000,
            icon: '✅',
            ...options
        };
        
        this.toast.success(message, config);
    }

    // Toast de sucesso para vitórias/prêmios
    win(message, options = {}) {
        const config = {
            ...ToastHelper.getBaseConfig(),
            position: 'top-center',
            timeout: 8000,
            icon: '🏆',
            className: 'toast-success-win',
            ...options
        };
        
        this.toast.success(message, config);
    }

    // Toast de erro
    error(message, options = {}) {
        const config = {
            ...ToastHelper.getBaseConfig(),
            position: 'top-center',
            timeout: 6000,
            icon: '⚠️',
            ...options
        };
        
        this.toast.error(message, config);
    }

    // Toast de aviso
    warning(message, options = {}) {
        const config = {
            ...ToastHelper.getBaseConfig(),
            position: 'top-center',
            timeout: 4000,
            icon: '⚠️',
            ...options
        };
        
        this.toast.warning(message, config);
    }

    // Toast de informação
    info(message, options = {}) {
        const config = {
            ...ToastHelper.getBaseConfig(),
            position: 'top-center',
            timeout: 4000,
            icon: 'ℹ️',
            ...options
        };
        
        this.toast.info(message, config);
    }

    // Toast específico para operações de compra
    purchase(message, options = {}) {
        const config = {
            ...ToastHelper.getBaseConfig(),
            position: 'top-right',
            timeout: 4000,
            icon: '🎫',
            ...options
        };
        
        this.toast.success(message, config);
    }

    // Toast específico para operações monetárias
    money(message, options = {}) {
        const config = {
            ...ToastHelper.getBaseConfig(),
            position: 'top-right',
            timeout: 5000,
            icon: '💰',
            ...options
        };
        
        this.toast.success(message, config);
    }

    // Toast específico para jogos
    game(message, type = 'info', options = {}) {
        const icons = {
            success: '🎮',
            error: '🚫',
            warning: '⚠️',
            info: '🎯'
        };

        const config = {
            ...ToastHelper.getBaseConfig(),
            position: 'top-center',
            timeout: 4000,
            icon: icons[type] || '🎮',
            ...options
        };
        
        this.toast[type](message, config);
    }

    // Toast de loading (informativo)
    loading(message, options = {}) {
        const config = {
            ...ToastHelper.getBaseConfig(),
            position: 'top-center',
            timeout: 3000,
            icon: '⏳',
            hideProgressBar: true,
            ...options
        };
        
        this.toast.info(message, config);
    }
}

// Função para usar em Vue components
export function useToastHelper() {
    return {
        install(app) {
            app.config.globalProperties.$toastHelper = new ToastHelper(app.config.globalProperties.$toast);
        }
    };
}

// Export de métodos estáticos para uso direto
export const toast = {
    success: (message, options = {}) => {
        // Para uso quando o this.$toast está disponível globalmente
        if (typeof window !== 'undefined' && window.app && window.app.$toast) {
            const helper = new ToastHelper(window.app.$toast);
            helper.success(message, options);
        }
    },
    error: (message, options = {}) => {
        if (typeof window !== 'undefined' && window.app && window.app.$toast) {
            const helper = new ToastHelper(window.app.$toast);
            helper.error(message, options);
        }
    },
    warning: (message, options = {}) => {
        if (typeof window !== 'undefined' && window.app && window.app.$toast) {
            const helper = new ToastHelper(window.app.$toast);
            helper.warning(message, options);
        }
    },
    info: (message, options = {}) => {
        if (typeof window !== 'undefined' && window.app && window.app.$toast) {
            const helper = new ToastHelper(window.app.$toast);
            helper.info(message, options);
        }
    }
};

export default ToastHelper;
