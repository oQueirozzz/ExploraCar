function toggleInfoTab() {
    const infoTab = document.getElementById('info-tab');
    infoTab.style.display = infoTab.style.display === 'block' ? 'none' : 'block';
}

function toggleHelpTab() {
    const helpTab = document.getElementById('help-tab');
    helpTab.style.display = helpTab.style.display === 'block' ? 'none' : 'block';
}

function toggleLogoutTab() {
    const logoutTab = document.getElementById('logout-tab');
    logoutTab.style.display = logoutTab.style.display === 'block' ? 'none' : 'block';
}
let currentIndex = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.carousel-item');
    const indicators = document.querySelectorAll('.indicator');

    // Corrige o índice se estiver fora dos limites
    if (index >= slides.length) currentIndex = 0;
    if (index < 0) currentIndex = slides.length - 1;

    const offset = -currentIndex * 100;
    document.querySelector('.carousel-inner').style.transform = `translateX(${offset}%)`;

    // Remover a classe 'active' de todos os indicadores e aplicar apenas ao atual
    indicators.forEach((indicator, i) => {
        if (i === currentIndex) {
            indicator.classList.add('active');
        } else {
            indicator.classList.remove('active');
        }
    });
}

function nextSlide() {
    currentIndex++;
    showSlide(currentIndex);
}

function prevSlide() {
    currentIndex--;
    showSlide(currentIndex);
}

function goToSlide(index) {
    currentIndex = index;
    showSlide(currentIndex);
}
document.querySelectorAll('.carousel-cars').forEach(carouselCars => {
    const track = carouselCars.querySelector('.carousel-track');
    const slides = Array.from(track.children); // Corrigido o nome
    const anteriorButton = carouselCars.querySelector('.anterior');
    const proximoButton = carouselCars.querySelector('.proximo');
    const larguraSlide = slides[0].getBoundingClientRect().width;

    let principalCorrente = 0;

    // Função para mover o carrossel
    function moveToSlide(principal) {
        track.style.transition = 'transform 0.5s ease-in-out';
        track.style.transform = `translateX(-${principal * larguraSlide}px)`;
    }

    // Função de transição para o próximo slide
    proximoButton.addEventListener('click', () => {
        if (principalCorrente === slides.length - 1) {
            principalCorrente = 0; // Loop infinito: volta ao primeiro slide
        } else {
            principalCorrente++;
        }
        moveToSlide(principalCorrente);
    });

    // Função de transição para o slide anterior
    anteriorButton.addEventListener('click', () => { 
        if (principalCorrente === 0) {
            principalCorrente = slides.length - 1; // Loop infinito: volta ao último slide
        } else {
            principalCorrente--;
        }
        moveToSlide(principalCorrente);
    });

    // Inicia a visualização no primeiro slide
    moveToSlide(principalCorrente);
});

  
