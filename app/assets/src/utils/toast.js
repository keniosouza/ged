/** Classe Desejada */
class Toast {

    /** Método construtor */
    constructor(options) {

        /** Parâmetros da classe */
        this._options = options;

        /** Verificação de operação */
        this.create();

    }

    /** Defino a cor do Toat */
    setBackground(background) {

        // Operador ternário para verificar se a variável está preenchida
        var _background = (background !== undefined && background !== null && background !== "") ? 'text-bg-' + background : '';

        /** Retorno da informação */
        return _background;

    }

    /** Criação do Modal */
    create() {

        /** Montagem da estrutura HTML */
        const ToastHtml = `<div class="toast-container position-fixed bottom-0 end-0 p-3 start-50 translate-middle-x">
                            <div class="toast align-items-center ${this.setBackground(this._options.background)} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        ${this._options.text}
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>`;

        // Obtenha uma referência ao elemento DOM com o ID 'wrapper-modal'
        var wrapperToast = document.getElementById('wrapper-toast');

        // Verifique se o elemento foi encontrado
        if (wrapperToast) {

            // Use um loop while para remover todos os filhos do elemento (limpar o conteúdo da div)
            while (wrapperToast.firstChild) {

                wrapperToast.removeChild(wrapperToast.firstChild);

            }

            // Utilize o método innerHTML para definir o conteúdo HTML da div
            // O conteúdo é definido com base no valor da variável 'ToastHtml'
            wrapperToast.innerHTML = ToastHtml;

            // Obtém todos os elementos com a classe 'toast' e converte o NodeList em um array
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));

            // Cria uma lista de objetos Toast a partir dos elementos encontrados
            var toastList = toastElList.map(function(toastEl) {

                // Para cada elemento, cria um novo objeto Toast utilizando a biblioteca Bootstrap
                return new bootstrap.Toast(toastEl);

            });

            // Para cada objeto Toast na lista, exibe a notificação
            toastList.forEach(function(toast) {

                // O método 'show' é chamado para exibir cada notificação
                toast.show();

            });

        }

    }

    /** Remoção do Toast */
    destroy()
    {

        // Obtenha uma referência ao elemento DOM com o ID 'wrapper-modal'
        var wrapperToast = document.getElementById('wrapper-toast');

        // Verifique se o elemento foi encontrado
        if (wrapperToast) {

            // Use um loop while para remover todos os filhos do elemento (limpar o conteúdo da div)
            while (wrapperToast.firstChild) {

                wrapperToast.removeChild(wrapperToast.firstChild);

            }

        }

    }

}