/**
 * Prepara um menu de contexto para um elemento específico.
 *
 * @param {string} _document O ID do elemento DOM no qual o menu de contexto será ativado.
 * @param {string} _menu O ID do menu de contexto que será exibido.
 */
function PrepareContextMenu(_document, _menu) {

    // Obtém o elemento do documento pelo ID
    const ele = document.getElementById(_document);

    // Obtém o elemento que representa o menu pelo ID
    const menu = document.getElementById(_menu);

    // Adiciona um ouvinte de evento para o clique do botão direito no elemento
    ele.addEventListener('contextmenu', function (e) {

        // Previne o comportamento padrão do navegador (menu de contexto padrão)
        e.preventDefault();

        // Seleciona todos os elementos que possuem a classeExistente
        const elementos = document.getElementsByClassName('container__menu');

        // Itera sobre cada elemento e adiciona a novaClasse
        for (let i = 0; i < elementos.length; i++) {

            elementos[i].classList.add('container__menu--hidden');

        }

        // Calcula a posição onde o menu de contexto deve aparecer baseado na posição do clique
        const rect = ele.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // Define a posição do menu de contexto
        menu.style.top = y + 'px';
        menu.style.left = x + 'px';

        // Mostra o menu de contexto
        menu.classList.remove('container__menu--hidden');
        menu.classList.add('animate');
        menu.classList.add('slideIn');

        // Adiciona um ouvinte para qualquer clique no documento
        document.addEventListener('click', documentClickHandler);

    });

    /**
     * Função que manipula cliques no documento para esconder o menu de contexto
     * quando o clique ocorre fora do menu.
     */
    const documentClickHandler = function (e) {

        // Verifica se o clique foi fora do menu de contexto
        const isClickedOutside = !menu.contains(e.target);

        if (isClickedOutside) {

            // Adiciona a classe para esconder o menu
            menu.classList.add('container__menu--hidden');

            // Remove as classes de animação
            menu.classList.remove('animate');
            menu.classList.remove('slideIn');

            // Remove o ouvinte de eventos para evitar chamadas desnecessárias
            document.removeEventListener('click', documentClickHandler);

        }

    };

}
