<?php $title = '404 - Page Not Found'; ?>

<div class="error-container">
    <div class="error-card">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you are looking for doesn't exist or has been moved.</p>
        
        <div class="error-actions">
            <a href="<?php echo url('dashboard'); ?>" class="btn-primary">
                ← Back to Dashboard
            </a>
            <button onclick="history.back()" class="btn-secondary">
                Go Back
            </button>
        </div>
    </div>
</div>

<style>
.error-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 80vh;
    padding: 20px;
}

.error-card {
    background: white;
    padding: 50px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 500px;
    width: 100%;
}

.error-card h1 {
    font-size: 120px;
    margin: 0;
    color: #2563eb;
    line-height: 1;
    font-weight: 700;
    text-shadow: 0 10px 20px rgba(37,99,235,0.2);
}

.error-card h2 {
    font-size: 28px;
    color: #1e293b;
    margin: 0 0 15px 0;
}

.error-card p {
    color: #64748b;
    margin-bottom: 30px;
    font-size: 16px;
}

.error-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.error-actions .btn-primary,
.error-actions .btn-secondary {
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
}

.error-actions .btn-primary {
    background: #2563eb;
    color: white;
}

.error-actions .btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

.error-actions .btn-secondary {
    background: #f1f5f9;
    color: #475569;
    border: none;
    cursor: pointer;
}

.error-actions .btn-secondary:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

@media (max-width: 480px) {
    .error-card {
        padding: 30px 20px;
    }
    
    .error-card h1 {
        font-size: 80px;
    }
    
    .error-card h2 {
        font-size: 22px;
    }
    
    .error-actions {
        flex-direction: column;
    }
}
</style>