<!-- Custom Login Header -->
<div class="custom-login-header">
    <div class="welcome-text">
        <p>Faça login para acessar o painel administrativo</p>
    </div>
</div>

<style>
.custom-login-header {
    text-align: center;
    margin-bottom: 2rem;
    animation: fadeInDown 1s ease-out;
}

.custom-login-header .welcome-text h2 {
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}

.custom-login-header .welcome-text p {
    color: #6b7280;
    font-size: 1rem;
    font-weight: 400;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (prefers-color-scheme: dark) {
    .custom-login-header .welcome-text p {
        color: #cbd5e1;
    }
}
</style>
