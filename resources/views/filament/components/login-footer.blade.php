<!-- Login Page Footer with Modern Elements -->
<div class="login-footer">
    <div class="login-features">
        <div class="feature-item">
            <div class="feature-icon">🔒</div>
            <span>Login Seguro</span>
        </div>
        <div class="feature-item">
            <div class="feature-icon">⚡</div>
            <span>Acesso Rápido</span>
        </div>
        <div class="feature-item">
            <div class="feature-icon">🛡️</div>
            <span>Protegido</span>
        </div>
    </div>
</div>

<style>
.login-footer {
    margin-top: 2rem;
    text-align: center;
    animation: fadeInUp 1.2s ease-out 0.5s both;
}

.login-features {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.feature-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    min-width: 100px;
}

.feature-item:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.feature-icon {
    font-size: 1.5rem;
    margin-bottom: 0.25rem;
}

.feature-item span {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.85rem;
    font-weight: 500;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 640px) {
    .login-features {
        gap: 1rem;
    }
    
    .feature-item {
        padding: 0.75rem;
        min-width: 80px;
    }
    
    .feature-icon {
        font-size: 1.25rem;
    }
    
    .feature-item span {
        font-size: 0.8rem;
    }
}

/* Dark mode adjustments */
@media (prefers-color-scheme: dark) {
    .feature-item {
        background: rgba(30, 41, 59, 0.3);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }
    
    .feature-item:hover {
        background: rgba(30, 41, 59, 0.4);
    }
    
    .feature-item span {
        color: rgba(248, 250, 252, 0.9);
    }
}
</style>
