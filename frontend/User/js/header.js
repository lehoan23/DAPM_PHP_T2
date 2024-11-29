const userName = document.getElementById('user-name');
    const dropdown = document.getElementById('user-dropdown');

    if (userName && dropdown) {
      userName.addEventListener('click', function (e) {
        e.stopPropagation(); 
        const isVisible = dropdown.style.display === 'block';
        dropdown.style.display = isVisible ? 'none' : 'block';
      });

      document.addEventListener('click', function () {
        if (dropdown) {
          dropdown.style.display = 'none';
        }
      });
    }