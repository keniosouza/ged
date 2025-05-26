/** Classe Desejad */
class Loader {

    /** Método construtor */
    constructor(options) {

        /** Parâmetros da classe */
        this._options = options;
        this._loader = null;
        this._newElement = null;

        /** Remove o backdrop anteriormente 
         * criado caso exista */

        if (document.getElementById("modal-backdrop")) {

            /** Carrega o elemento a ser removido */
            this._removeBackdrop = document.getElementById("modal-backdrop");

            /** Remove o elemento */
            this._removeBackdrop.parentNode.removeChild(this._removeBackdrop);
        }

    }

    enableOrDisableElement(selector) {

        // Obtém o elemento pelo seletor
        const elemento = document.getElementById(selector);

        // Verifica se o elemento existe
        if (elemento) {
            // Verifica se é um botão
            if (elemento.tagName.toLowerCase() === 'button') {
                if (elemento.disabled) {
                    elemento.disabled = false;
                    console.log('Botão habilitado.');
                } else {
                    elemento.disabled = true;
                    console.log('Botão desabilitado.');
                }
            }
            // Verifica se é um link
            else if (elemento.tagName.toLowerCase() === 'a') {
                if (elemento.style.pointerEvents === 'none') {
                    elemento.style.pointerEvents = 'auto'; // Habilita o clique
                    elemento.style.color = ''; // Restaura a cor original
                    elemento.removeAttribute('aria-disabled'); // Remove o atributo de acessibilidade
                    console.log('Link habilitado.');
                } else {
                    elemento.style.pointerEvents = 'none'; // Desabilita o clique
                    elemento.style.color = 'gray'; // Muda a cor para indicar que está desativado (opcional)
                    elemento.setAttribute('aria-disabled', 'true'); // Acessibilidade
                    console.log('Link desabilitado.');
                }
            } else {
                console.log('Elemento não é um botão ou um link.');
            }
        } else {
            console.log('Elemento não encontrado.');
        }

    }

    /** Criação do Modal */
    create() {

        // Crio um identificador para o loader
        this._loader = gerarNumeroAleatorioInclusivo(1, 10);

        // Declaração de vairavel
        var LoaderHtml = null;

        // Verifico se existe alvo personalizado
        var target = this._options.target ? this._options.target : null;

        // Obtenha uma referência ao elemento DOM com o ID 'wrapper-modal'
        var element = document.getElementById(target);

        this.enableOrDisableElement(target);

        // Verifico o tipo de carregamento que devo colocar na tela
        switch (this._options.type) {

            // Apenas escrita fixa
            case 1:

                /**
                 * Montagem da estrutura HTML para exibição do loader.
                 * O loader é composto por um spinner e o conteúdo dinâmico definido em this._options.data.
                 */
                LoaderHtml = `<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span><span role="status">Aguarde</span>`;

                /**
                 * Guarda o valor antigo do conteúdo do elemento.
                 * Isso é feito para poder restaurar o conteúdo original após a remoção do loader.
                 * O conteúdo original é armazenado em um elemento oculto com o ID igual ao alvo do loader mais 'Bkp'.
                 */
                LoaderHtml += `<span id="${this._options.target}Bkp" class="d-none">${element.innerHTML.trim()}</span>`;
                break;

            // Apenas os placeholder
            case 2:

                // Crio o layout de carregamento da tela
                LoaderHtml = `<div class="app-content">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12">                                     
                                            <p class="card-text placeholder-glow m-4">
                                                <span class="placeholder col-9 placeholder-lg"></span>
                                                <span class="placeholder col-12 placeholder-lg"></span>
                                                <span class="placeholder col-6 placeholder-lg"></span>
                                                <span class="placeholder col-8 placeholder-lg"></span>
                                                <span class="placeholder col-12 placeholder-lg"></span>
                                                <span class="placeholder col-9 placeholder-lg"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                             </div>`;
                break;

            // Bloqeia a pagina
            case 3:

                /** Verifica se o elemento modal-backdrop não foi criado, 
                 * caso não tenha, cria o mesmo */
                if (!document.querySelector(".modal-backdrop")) {

                    // Cria o elemento (por exemplo, um <div>)
                    this._newElement = document.createElement("div");

                    // Adiciona conteúdo ao elemento
                    this._newElement.textContent = '';

                    // Define as classes do elemento
                    this._newElement.className = "modal-backdrop fade show";

                    // Define o id do elemento
                    this._newElement.id = "modal-backdrop";

                    // Define o atributo name do elemento
                    this._newElement.setAttribute("name", "modal-backdrop");

                    // Adiciona o elemento ao body
                    document.body.appendChild(this._newElement);

                    // Adiciona o spinner
                    this._newElement.innerHTML += '<div class="d-flex justify-content-center align-items-center vh-100"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                }

                break;


        }

        // Verifica se o elemento com o ID armazenado em this._options.target foi encontrado
        if (element) {

            // Define o conteúdo HTML da div como o LoaderHtml, exibindo assim o loader na interface do usuário
            element.innerHTML = LoaderHtml;

        }

    }

    /** Remoção do loader */
    destroy() {

        // Verifico se existe alvo personalizado
        var target = this._options.target ? this._options.target : 'app-main';

        this.enableOrDisableElement(target);

        // Verifico o tipo de carregamento que devo colocar na tela
        switch (this._options.type) {

            // Apenas escrita fixa
            case 1:

                // Seleciona o elemento com o ID 'targetBkp' concatenado com 'Bkp' e armazena-o em elementBkp.
                var elementBkp = document.getElementById(this._options.target + 'Bkp');

                /** Verifica se o elemento do backup foi informado */
                if (elementBkp) {

                    // Seleciona o elemento com o ID armazenado em this._options.target e armazena-o em element.
                    var element = document.getElementById(this._options.target);

                    // Define o conteúdo HTML do elemento element como o conteúdo HTML do elemento elementBkp, removendo espaços em branco do início e do final.
                    element.innerHTML = elementBkp.innerHTML.trim();
                }
                break;
        }

    }

}