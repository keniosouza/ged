/**
 * Prepara um formulário de contexto para ser exibido quando o botão direito do mouse é clicado em um elemento específico.
 * @param {string} _document - O ID do elemento HTML onde o menu de contexto será ativado.
 * @param {string} _menu - O ID do elemento HTML que representa o menu de contexto.
 * @param {object} _request - O objeto contendo informações sobre a requisição a ser enviada quando o menu de contexto é acionado.
 */
function PrepareContextForm(_document, _menu, _request) {

    // Obtém as referências para os elementos HTML especificados pelos IDs fornecidos
    const ele = document.getElementById(_document);
    const menu = document.getElementById(_menu);

    // Adiciona um ouvinte de evento para o evento de menu de contexto no elemento especificado
    ele.addEventListener('contextmenu', function (e) {

        // Impede o comportamento padrão do menu de contexto do navegador
        e.preventDefault();

        // Seleciona todos os elementos que possuem a classeExistente
        const elementos = document.getElementsByClassName('container__form');

        // Itera sobre cada elemento e adiciona a novaClasse
        for (let i = 0; i < elementos.length; i++) {

            elementos[i].classList.add('container__form--hidden');

        }

        // Obtém as coordenadas do mouse em relação ao elemento alvo
        const rect = ele.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // Define a posição do menu de contexto com base nas coordenadas do mouse
        menu.style.top = y + 'px';
        menu.style.left = x + 'px';

        // Exibe o menu de contexto removendo a classe 'container__form--hidden' e adicionando classes de animação
        menu.classList.remove('container__form--hidden');
        menu.classList.add('animate');
        menu.classList.add('slideIn');

        // Adiciona um ouvinte de evento para clicar no documento para ocultar o menu de contexto quando clicar fora dele
        document.addEventListener('click', documentClickHandler);

        /** Envie a Requisição desejada */
        SendRequest(_request, {

            target: _menu

        });

    });

    // Função para ocultar o menu de contexto quando clicar fora dele
    const documentClickHandler = function (e) {

        // Verifica se o clique foi fora do menu de contexto
        const isClickedOutside = !menu.contains(e.target);

        //Verifico se foi clicado do lado de fora
        if (isClickedOutside) {

            // Oculta o menu de contexto e remove as classes de animação
            menu.classList.remove('animate');
            menu.classList.remove('slideIn');
            menu.classList.add('animate');
            menu.classList.add('slideOut');
            menu.classList.add('container__form--hidden');

            // Remove o ouvinte de evento de clique no documento
            document.removeEventListener('click', documentClickHandler);

        }

    };

}
