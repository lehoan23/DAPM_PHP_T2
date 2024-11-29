//Hiển thị % ở progess
const progressBar = document.getElementById('progress-bardetails');
const progressText = document.getElementById('progress-textdetails');


function updateProgressText() {
    const progressValue = progressBar.value; 
    const maxValue = progressBar.max;
    const progressPercentage = (progressValue / maxValue) * 100;
    const clampedPercentage = Math.min(Math.max(progressPercentage, 0), 100);
    progressText.style.left = `calc(${clampedPercentage}% - 10px)`; // Đặt text theo phần trăm của thanh tiến độ
    progressText.textContent = `${clampedPercentage.toFixed(0)}%`;
}

window.addEventListener('load', updateProgressText);

//Hiển thị form
document.addEventListener('DOMContentLoaded', function () {
    const donateButton = document.querySelector('.btn-donate');
    const donateFormContainer = document.getElementById('donate-form-container');
    const closeFormButton = document.getElementById('close-donate-form');
    const donateForm = document.getElementById('donate-form');

    donateButton.addEventListener('click', function (event) {
        event.preventDefault();
        donateFormContainer.style.display = 'flex';
    });

    donateForm.addEventListener('submit', function (e) {
        e.preventDefault();
        alert('Cảm ơn bạn đã ủng hộ!');
        donateFormContainer.style.display = 'none';
    });

    donateFormContainer.addEventListener('click', function (e) {
        if (e.target === donateFormContainer) {
            donateFormContainer.style.display = 'none';
        }
    });
});



