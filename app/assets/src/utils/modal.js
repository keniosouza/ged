var modalId = null;

class Modal {

    /** Método construtor */
    constructor(options) {

        /** Remove o backdrop anteriormente 
         * criado caso exista */

        if (document.getElementById("modal-backdrop")) {

            /** Carrega o elemento a ser removido */
            this._removeBackdrop = document.getElementById("modal-backdrop");

            /** Remove o elemento */
            this._removeBackdrop.parentNode.removeChild(this._removeBackdrop);
        }

        if (document.querySelector(".modal-backdrop")) {

            /** Carrega o elemento a ser removido */
            this._removeBackdrop = document.querySelector(".modal-backdrop");

            /** Remove o elemento */
            this._removeBackdrop.parentNode.removeChild(this._removeBackdrop);
        }

        /** Parâmetros da classe */
        this._options = options;

        /** Verificação de operação */
        this.create();

    }

    /** Define o tipo do Modal */
    setType(type) {

        /** Verifica se está preenchido */
        return type !== null ? type : null;

    }

    /** Define o tamanho do modal */
    setSize(size) {

        /** Retorna o valor do Modal ou null se não estiver preenchido */
        return size !== null ? 'modal-' + size : null;

    }

    /** Criação do Modal */
    create() {

        /** Montagem do ID */
        const modalId = 'modal_' + Math.floor(Math.random() * 100) + 1;

        /** Montagem da estrutura HTML */
        const html = `<div class="modal fade" id="${modalId}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable ${this.setSize(this._options.size)}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    ${this._options.title !== '' ? '<h4 class="modal-title text-center"><b>' + this._options.title + '</b></h4>' : ''} 
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-break">
                                    ${this._options.document ? `
                                        <div class="card">
                                            <div class="card-body">
                                                <iframe src="${this._options.document}" data-bs-title="Iframe Example" class="w-100" height=500px></iframe>
                                            </div>
                                        </div>
                                    ` : `
                                        ${this.setType(this._options.type) !== null ? `
                                            <div class="text-center mb-2">
                                                <h1 class="${this.setType(this._options.type)}"></h1>
                                            </div>
                                        ` : ''}
                                        <div>
                                            ${this._options.data}
                                        </div>
                                    `}
                                </div>
                                ${this._options.procedure ? `
                                    <div class="modal-footer flex-column border-top-0">

                                        <div class="d-flex gap-2 text-center pt-4">

                                            <button type="button" class="w-50 btn btn-light-brand" data-bs-dismiss="modal">
                                                <i class="bi bi-x me-1"></i>Cancelar
                                            </button>

                                            <button type="button" class="w-50 btn btn-primary" data-bs-dismiss="modal" onclick="${this._options.procedure}" id="btnModalPage">
                                                <i class="bi bi-check me-1"></i>Continuar
                                            </button>

                                        </div>

                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>`;

        // Obtenha uma referência ao elemento DOM com o ID 'wrapper-modal'
        var wrapperModal = document.getElementById('wrapper-modal');

        // Verifique se o elemento foi encontrado
        if (wrapperModal) {

            // Use um loop while para remover todos os filhos do elemento (limpar o conteúdo da div)
            while (wrapperModal.firstChild) {

                wrapperModal.removeChild(wrapperModal.firstChild);

            }

        }

        // Verifique se o elemento foi encontrado
        if (wrapperModal) {

            /** Preenchimento da div desejada **/
            $('#wrapper-modal').html(html);

        }

        var myModal = new bootstrap.Modal(document.getElementById(modalId), {
            keyboard: false
        });

        /** Exibição do Modal */
        //$('#' + modalId).fadeIn('show');
        myModal.show();

        //Limpa os objetos
        this._options = null;

    }

}