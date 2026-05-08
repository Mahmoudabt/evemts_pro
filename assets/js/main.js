// =====================================================
// لمسات إبداعية: Dark Mode + Scroll to Top
// =====================================================

// 1. Dark Mode Toggle
(function() {
    // إنشاء زر الوضع المظلم
    const darkModeBtn = document.createElement('button');
    darkModeBtn.innerHTML = '🌙';
    darkModeBtn.id = 'darkModeToggle';
    darkModeBtn.setAttribute('aria-label', 'تبديل الوضع المظلم');
    darkModeBtn.style.cssText = `
        position: fixed;
        bottom: 80px;
        left: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #0d6efd;
        color: white;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    `;
    document.body.appendChild(darkModeBtn);
    
    // التحقق من التفضيل المخزن
    const darkModePreference = localStorage.getItem('darkMode');
    if (darkModePreference === 'enabled') {
        enableDarkMode();
        darkModeBtn.innerHTML = '☀️';
    }
    
    // وظيفة تفعيل الوضع المظلم
    function enableDarkMode() {
        document.body.style.backgroundColor = '#1a1a2e';
        document.body.style.color = '#f0f0f0';
        
        // تغيير خلفية البطاقات والعناصر
        const cards = document.querySelectorAll('.card, .filter-card');
        cards.forEach(card => {
            card.style.backgroundColor = '#2d2d44';
            card.style.color = '#e0e0e0';
        });
        
        const navbars = document.querySelectorAll('.navbar');
        navbars.forEach(nav => {
            nav.style.backgroundColor = '#0f0f1a';
        });
        
        const footers = document.querySelectorAll('footer');
        footers.forEach(footer => {
            footer.style.backgroundColor = '#0f0f1a';
        });
        
        const tables = document.querySelectorAll('table, .table');
        tables.forEach(table => {
            table.style.backgroundColor = '#2d2d44';
            table.style.color = '#e0e0e0';
        });
        
        const modals = document.querySelectorAll('.modal-content');
        modals.forEach(modal => {
            modal.style.backgroundColor = '#2d2d44';
            modal.style.color = '#e0e0e0';
        });
        
        localStorage.setItem('darkMode', 'enabled');
    }
    
    // وظيفة إلغاء الوضع المظلم
    function disableDarkMode() {
        document.body.style.backgroundColor = '';
        document.body.style.color = '';
        
        const cards = document.querySelectorAll('.card, .filter-card');
        cards.forEach(card => {
            card.style.backgroundColor = '';
            card.style.color = '';
        });
        
        const navbars = document.querySelectorAll('.navbar');
        navbars.forEach(nav => {
            nav.style.backgroundColor = '';
        });
        
        const footers = document.querySelectorAll('footer');
        footers.forEach(footer => {
            footer.style.backgroundColor = '';
        });
        
        const tables = document.querySelectorAll('table, .table');
        tables.forEach(table => {
            table.style.backgroundColor = '';
            table.style.color = '';
        });
        
        const modals = document.querySelectorAll('.modal-content');
        modals.forEach(modal => {
            modal.style.backgroundColor = '';
            modal.style.color = '';
        });
        
        localStorage.setItem('darkMode', 'disabled');
    }
    
    // حدث النقر على الزر
    darkModeBtn.addEventListener('click', function() {
        const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
        if (isDarkMode) {
            disableDarkMode();
            darkModeBtn.innerHTML = '🌙';
        } else {
            enableDarkMode();
            darkModeBtn.innerHTML = '☀️';
        }
    });
})();

// 2. Scroll to Top Button
(function() {
    // إنشاء زر العودة للأعلى
    const scrollBtn = document.createElement('button');
    scrollBtn.innerHTML = '⬆️';
    scrollBtn.id = 'scrollToTop';
    scrollBtn.setAttribute('aria-label', 'العودة للأعلى');
    scrollBtn.style.cssText = `
        position: fixed;
        bottom: 20px;
        left: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #6c757d;
        color: white;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        opacity: 0;
        visibility: hidden;
    `;
    document.body.appendChild(scrollBtn);
    
    // إظهار أو إخفاء الزر حسب التمرير
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            scrollBtn.style.opacity = '1';
            scrollBtn.style.visibility = 'visible';
        } else {
            scrollBtn.style.opacity = '0';
            scrollBtn.style.visibility = 'hidden';
        }
    });
    
    // التمرير السلس للأعلى عند النقر
    scrollBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
})();