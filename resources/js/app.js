import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

var accountForm = document.getElementById('accountForm');
if (accountForm !== null) {
    accountForm.addEventListener('submit', function (event) {
        var nameInput = document.getElementById('name');
        var validationModal = document.getElementById('validationModal');
        var closeModal = document.getElementById('closeModal');

        if (nameInput.value.trim() === '') {
            // Prepara o modal para ser visível, mas ainda transparente
            validationModal.classList.remove('hidden');
            validationModal.classList.add('fade');

            // Atraso para permitir que o navegador prepare o modal para animação
            setTimeout(() => {
                validationModal.classList.add('fade-in');
            }, 10);

            event.preventDefault();
        }

        closeModal.addEventListener('click', function () {
            // Iniciar fade out
            validationModal.classList.remove('fade-in');
            validationModal.classList.add('fade');

            // Esconder modal após a transição
            setTimeout(function () {
                validationModal.classList.add('hidden');
            }, 500); // Tempo igual à duração da transição
        });
    });
}

var opening_balance = document.querySelector('.value_mask');
if (opening_balance !== null) {
    opening_balance.addEventListener('input', function (e) {
        var value = e.target.value.replace(/\D/g, ''); // Remove caracteres não numéricos
        value = (value / 100).toFixed(2) + ''; // Divide por 100 e fixa duas casas decimais
        value = value.replace('.', ','); // Substitui ponto por vírgula
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Adiciona pontos como separadores de milhar
        e.target.value = value;
    });
}
