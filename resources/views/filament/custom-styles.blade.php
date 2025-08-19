<style>
/* Custom Dark Theme for Filament Admin Panel */

/* Sidebar customization */
.fi-sidebar {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
    border-right: 1px solid rgba(139, 92, 246, 0.1) !important;
}

/* Navigation items */
.fi-sidebar-nav-item {
    transition: all 0.3s ease !important;
}

.fi-sidebar-nav-item:hover {
    background: rgba(139, 92, 246, 0.1) !important;
    border-radius: 8px !important;
    transform: translateX(4px) !important;
}

/* Navigation group labels */
.fi-sidebar-nav-group-label {
    color: #a855f7 !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    font-size: 0.75rem !important;
}

/* Navigation item labels */
.fi-sidebar-nav-item-label {
    color: #e2e8f0 !important;
    font-weight: 500 !important;
}

/* Active navigation item */
.fi-sidebar-nav-item[aria-current="page"] {
    background: linear-gradient(90deg, rgba(139, 92, 246, 0.2), rgba(139, 92, 246, 0.1)) !important;
    border-right: 3px solid #a855f7 !important;
}

.fi-sidebar-nav-item[aria-current="page"] .fi-sidebar-nav-item-label {
    color: #a855f7 !important;
    font-weight: 600 !important;
}

/* Icons customization */
.fi-sidebar-nav-item-icon {
    color: #94a3b8 !important;
    transition: all 0.3s ease !important;
}

.fi-sidebar-nav-item:hover .fi-sidebar-nav-item-icon {
    color: #a855f7 !important;
    transform: scale(1.1) !important;
}

.fi-sidebar-nav-item[aria-current="page"] .fi-sidebar-nav-item-icon {
    color: #a855f7 !important;
}

/* Group icons */
.fi-sidebar-nav-group-icon {
    color: #a855f7 !important;
}

/* Main content area */
.fi-main {
    background: #0f172a !important;
}

/* Header */
.fi-topbar {
    background: linear-gradient(90deg, #1e293b 0%, #334155 100%) !important;
    border-bottom: 1px solid rgba(139, 92, 246, 0.1) !important;
    backdrop-filter: blur(10px) !important;
}

/* Cards and panels */
.fi-card, .fi-section {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
    border: 1px solid rgba(139, 92, 246, 0.1) !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important;
}

/* Tables */
.fi-ta-table {
    background: #1e293b !important;
}

.fi-ta-row:hover {
    background: rgba(139, 92, 246, 0.05) !important;
}

/* Scrollbar customization */
.fi-sidebar::-webkit-scrollbar {
    width: 6px;
}

.fi-sidebar::-webkit-scrollbar-track {
    background: rgba(139, 92, 246, 0.1);
    border-radius: 3px;
}

.fi-sidebar::-webkit-scrollbar-thumb {
    background: #a855f7;
    border-radius: 3px;
}

.fi-sidebar::-webkit-scrollbar-thumb:hover {
    background: #8b5cf6;
}

/* Dark mode body override */
body {
    background: #0f172a !important;
}

/* Specific icon colors for different sections */
[data-group="Modulos"] .fi-sidebar-nav-item-icon {
    color: #f59e0b !important; /* Amber for Modules */
}

[data-group="Meus Jogos"] .fi-sidebar-nav-item-icon {
    color: #10b981 !important; /* Emerald for Games */
}

[data-group="Pagamentos"] .fi-sidebar-nav-item-icon {
    color: #06b6d4 !important; /* Cyan for Payments */
}

[data-group="Afiliados"] .fi-sidebar-nav-item-icon {
    color: #8b5cf6 !important; /* Purple for Affiliates */
}

[data-group="Customização"] .fi-sidebar-nav-item-icon {
    color: #ec4899 !important; /* Pink for Customization */
}

[data-group="Definições"] .fi-sidebar-nav-item-icon {
    color: #6366f1 !important; /* Indigo for Settings */
}

[data-group="Usuários"] .fi-sidebar-nav-item-icon {
    color: #84cc16 !important; /* Lime for Users */
}

[data-group="maintenance"] .fi-sidebar-nav-item-icon {
    color: #ef4444 !important; /* Red for Maintenance */
}

/* ==============================================
   CUSTOM LOGIN PAGE STYLES - MODERN DESIGN
   ============================================== */

/* Login page background */
.fi-simple-layout {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    min-height: 100vh !important;
    position: relative !important;
    overflow: hidden !important;
}

/* Animated background particles */
.fi-simple-layout::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 100px 100px, 80px 80px, 120px 120px;
    animation: float 20s ease-in-out infinite;
    z-index: 1;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(1deg); }
}

/* Login card container */
.fi-simple-layout .fi-simple-main {
    position: relative !important;
    z-index: 2 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 2rem !important;
}

/* Login card - Reduced size */
.fi-simple-layout .fi-simple-card {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 20px !important;
    box-shadow: 
        0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    padding: 2rem !important;
    width: 100% !important;
    max-width: 400px !important;
    transform: translateY(0) !important;
    transition: all 0.3s ease !important;
    animation: slideInUp 0.6s ease-out !important;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fi-simple-layout .fi-simple-card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 
        0 25px 35px -5px rgba(0, 0, 0, 0.15),
        0 15px 15px -5px rgba(0, 0, 0, 0.06) !important;
}

/* Logo styling - Reduced size */
.fi-simple-layout .fi-logo {
    display: flex !important;
    justify-content: center !important;
    margin-bottom: 1.5rem !important;
}

.fi-simple-layout .fi-logo img {
    max-height: 60px !important;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1)) !important;
    transition: transform 0.3s ease !important;
}

.fi-simple-layout .fi-logo img:hover {
    transform: scale(1.02) !important;
}

/* Form header - Reduced size */
.fi-simple-layout .fi-simple-header {
    text-align: center !important;
    margin-bottom: 1.5rem !important;
}

.fi-simple-layout .fi-simple-header h1 {
    color: #1f2937 !important;
    font-size: 1.5rem !important;
    font-weight: 600 !important;
    margin-bottom: 0.5rem !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
}

.fi-simple-layout .fi-simple-header p {
    color: #6b7280 !important;
    font-size: 0.9rem !important;
    font-weight: 400 !important;
}

/* Form inputs - Fixed sizing and positioning */
.fi-simple-layout .fi-field-wrapper {
    margin-bottom: 1.25rem !important;
}

.fi-simple-layout .fi-input-wrapper {
    position: relative !important;
    display: block !important;
}

.fi-simple-layout .fi-input {
    width: 100% !important;
    padding: 0.875rem 1rem !important;
    border: 1px solid rgba(102, 126, 234, 0.3) !important;
    border-radius: 12px !important;
    font-size: 0.95rem !important;
    font-weight: 400 !important;
    color: #1f2937 !important;
    transition: all 0.3s ease !important;
    backdrop-filter: blur(10px) !important;
    box-sizing: border-box !important;
    display: block !important;
}

.fi-simple-layout .fi-input:focus {
    outline: none !important;
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
    transform: none !important;
}

.fi-simple-layout .fi-input::placeholder {
    color: #9ca3af !important;
    font-weight: 400 !important;
}

/* Submit button - Reduced size */
.fi-simple-layout .fi-btn-primary {
    width: 100% !important;
    padding: 0.875rem 1.25rem !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
    border-radius: 12px !important;
    color: white !important;
    font-size: 0.95rem !important;
    font-weight: 600 !important;
    text-transform: none !important;
    letter-spacing: 0.025em !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3) !important;
    cursor: pointer !important;
    position: relative !important;
    overflow: hidden !important;
}

.fi-simple-layout .fi-btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.fi-simple-layout .fi-btn-primary:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4) !important;
}

.fi-simple-layout .fi-btn-primary:hover::before {
    left: 100%;
}

.fi-simple-layout .fi-btn-primary:active {
    transform: translateY(0) !important;
}

/* Remember me checkbox */
.fi-simple-layout .fi-checkbox-wrapper {
    display: flex !important;
    align-items: center !important;
    margin: 1.5rem 0 !important;
}

.fi-simple-layout .fi-checkbox {
    margin-right: 0.75rem !important;
    transform: scale(1.2) !important;
    accent-color: #667eea !important;
}

.fi-simple-layout .fi-checkbox-label {
    color: #6b7280 !important;
    font-size: 0.95rem !important;
    font-weight: 500 !important;
    cursor: pointer !important;
}

/* Links */
.fi-simple-layout a {
    color: #667eea !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
}

.fi-simple-layout a:hover {
    color: #764ba2 !important;
    text-decoration: underline !important;
}

/* Error messages */
.fi-simple-layout .fi-field-wrapper-error-message {
    background: rgba(239, 68, 68, 0.1) !important;
    border: 1px solid rgba(239, 68, 68, 0.2) !important;
    border-radius: 12px !important;
    padding: 0.75rem 1rem !important;
    margin-top: 0.5rem !important;
    color: #dc2626 !important;
    font-weight: 500 !important;
    backdrop-filter: blur(10px) !important;
}

/* Success messages */
.fi-simple-layout .fi-notification {
    background: rgba(16, 185, 129, 0.1) !important;
    border: 1px solid rgba(16, 185, 129, 0.2) !important;
    border-radius: 12px !important;
    backdrop-filter: blur(10px) !important;
}

/* Dark mode adjustments for login */
@media (prefers-color-scheme: dark) {
    .fi-simple-layout .fi-simple-card {
        background: rgba(30, 41, 59, 0.95) !important;
        border: 1px solid rgba(148, 163, 184, 0.2) !important;
    }
    
    .fi-simple-layout .fi-simple-header h1 {
        color: #f8fafc !important;
    }
    
    .fi-simple-layout .fi-simple-header p {
        color: #cbd5e1 !important;
    }
    
    .fi-simple-layout .fi-input {
        background: rgba(30, 41, 59, 0.8) !important;
        color: #f8fafc !important;
        border-color: rgba(139, 92, 246, 0.3) !important;
    }
    
    .fi-simple-layout .fi-input::placeholder {
        color: #64748b !important;
    }
    
    .fi-simple-layout .fi-checkbox-label {
        color: #cbd5e1 !important;
    }
}

/* Responsive design */
@media (max-width: 640px) {
    .fi-simple-layout .fi-simple-main {
        padding: 1rem !important;
    }
    
    .fi-simple-layout .fi-simple-card {
        padding: 2rem !important;
        border-radius: 20px !important;
    }
    
    .fi-simple-layout .fi-simple-header h1 {
        font-size: 1.75rem !important;
    }
}
</style>
