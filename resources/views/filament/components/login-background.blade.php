<!-- Modern Login Page Background Elements -->
<div class="login-bg-elements">
    <!-- Geometric shapes for visual appeal -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>
    
    <!-- Version info -->
    <div class="version-info">
        <span></span>
    </div>
</div>

<style>
.login-bg-elements {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
    overflow: hidden;
}

.bg-shape {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    animation: float 20s ease-in-out infinite;
}

.shape-1 {
    width: 300px;
    height: 300px;
    top: 10%;
    right: -10%;
    animation-delay: 0s;
    animation-duration: 25s;
}

.shape-2 {
    width: 200px;
    height: 200px;
    bottom: 20%;
    left: -5%;
    animation-delay: -8s;
    animation-duration: 30s;
}

.shape-3 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 10%;
    animation-delay: -15s;
    animation-duration: 20s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg) scale(1);
        opacity: 0.3;
    }
    25% {
        transform: translateY(-20px) rotate(90deg) scale(1.1);
        opacity: 0.5;
    }
    50% {
        transform: translateY(-10px) rotate(180deg) scale(0.9);
        opacity: 0.7;
    }
    75% {
        transform: translateY(-30px) rotate(270deg) scale(1.05);
        opacity: 0.4;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .shape-1 {
        width: 200px;
        height: 200px;
        right: -15%;
    }
    
    .shape-2 {
        width: 150px;
        height: 150px;
        left: -10%;
    }
    
    .shape-3 {
        width: 100px;
        height: 100px;
    }
    
    .version-info {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        bottom: 1rem;
    }
}

/* Reduce motion for accessibility */
@media (prefers-reduced-motion: reduce) {
    .bg-shape,
    .version-info {
        animation: none !important;
    }
    
    .bg-shape {
        opacity: 0.2;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .bg-shape {
        display: none;
    }
    
    .version-info {
        background: #000;
        color: #fff;
        border: 1px solid #fff;
    }
}
</style>
