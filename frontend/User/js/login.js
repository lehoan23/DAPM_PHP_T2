document.getElementById('login')?.addEventListener('submit', function(event) {
    event.preventDefault(); 

    const email = document.querySelector('input[type="text"]').value;
    const password = document.querySelector('input[type="password"]').value;

    if (email && password) { 

        localStorage.setItem('isLoggedIn', 'true');
        localStorage.setItem('userName', email); 

        window.location.href = './index.html'; 
    } else {
        
        const errorMessage = document.querySelector('.form__message');
        errorMessage.textContent = 'Thông tin đăng nhập không hợp lệ.';
    }
});
