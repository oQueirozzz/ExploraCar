document.addEventListener('DOMContentLoaded', function() {
    const dataNascimentoInput = document.getElementById('dataNascimento');

    dataNascimentoInput.addEventListener('input', function(event) {
        let value = this.value.replace(/\D/g, ''); // Remove qualquer caractere que não seja dígito

        if (value.length > 8) {
            value = value.slice(0, 8); // Limita a 8 dígitos
        }

        // Adiciona a máscara
        if (value.length >= 6) {
            value = value.replace(/^(\d{2})(\d{2})(\d{4})$/, '$1/$2/$3'); // DD/MM/AAAA
        } else if (value.length >= 4) {
            value = value.replace(/^(\d{2})(\d{2})$/, '$1/$2'); // DD/MM
        } else if (value.length >= 2) {
            value = value.replace(/^(\d{2})$/, '$1'); // DD
        }

        this.value = value; // Atualiza o campo de entrada

        const telefone = document.querySelector('#telefone');
        telefone.addEventListener('keyup', () => {
            let valor = telefone.value.replace(/\D+/g, '').slice(0, 11);
            valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            telefone.value = valor;
        });
        
    });
});

