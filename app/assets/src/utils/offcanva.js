class OffCanva {

    /** Método construtor */
    constructor(data) {

        /** Parâmetros da classe */
        this._data = data;

        /** Verificação de operação */
        this.create();

    }

    /** Criação do Modal */
    create() {

        /** Montagem da estrutura HTML */
        const html = `<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasRightLabel">
                                ${this._data.title}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body" id="OffCanvasBody">
                            ${new Request({"request" : {"path" : this._data.request}, "params" : this._data.params, "response" : {"target" : "OffCanvasBody"}})}
                        </div>
                      </div>`;

        // Obtenha uma referência ao elemento DOM com o ID 'wrapper-modal'
        var wrapperOffCanva = document.getElementById('wrapper-offcanva');

        // Verifique se o elemento foi encontrado
        if (wrapperOffCanva) {

            // Use um loop while para remover todos os filhos do elemento (limpar o conteúdo da div)
            while (wrapperOffCanva.firstChild) {

                wrapperOffCanva.removeChild(wrapperOffCanva.firstChild);

            }

        }

        // Verifique se o elemento foi encontrado
        if (wrapperOffCanva) {

            /** Preenchimento da div desejada **/
            $('#wrapper-offcanva').html(html);

        }

        // Depois da criação, exiba o Offcanvas
        const offcanvasElement = document.getElementById('offcanvasRight');

        // Verifico se o elemento existe
        if (offcanvasElement) {

            // Inicializa e exibe o Offcanvas usando a API do Bootstrap
            const bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);

            // Exibe o coneudo
            bsOffcanvas.show();

        }

    }

}