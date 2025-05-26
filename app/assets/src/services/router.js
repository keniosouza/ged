/** Classe desejada */
class Request {

    /** Método construtor */
    constructor(data, path = null) {

        /** Parâmetros da classe */
        this._data = data;
        this._path = path;
        this._loader = new Loader(data.loader);

        /** Remove o backdrop anteriormente 
        * criado caso exista */

        if (document.getElementById("modal-backdrop")) {

            /** Carrega o elemento a ser removido */
            this._removeBackdrop = document.getElementById("modal-backdrop");

            /** Remove o elemento */
            this._removeBackdrop.parentNode.removeChild(this._removeBackdrop);
        }

        /** Verifica se o elemento a ficar 
         * ativo foi informado */
        if (this._data.active) {

            /** Remove a classe de ativação do item anterior */
            document.querySelectorAll(".active").forEach(elemento => {
                elemento.classList.remove("active");
            });

            /** Aplica a classe de ativação no item */
            document.getElementById(this._data.active.btn).classList.add("active");
        }

        /** Verifico se o caminho da requisição foi informado */
        if (this._data.request.path) {

            /** Realizo a requisição desejada */
            this.send()

        }

    }

    /** Função para definir o cabeçalho da requisição */
    setHeader(data) {

        /** Defino o cabeçalho padrão de requisição */
        var header = {

            /** Formato de envio */
            method: 'post',

            /** Modo de envio */
            mode: 'cors',

            /** Defino o cabeçalho da requisição */
            headers: {

                /** Converto a string para parâmetros de url */
                "Content-Type": "application/x-www-form-urlencoded",

                /** Envio a contagem de caracteres */
                "Content-Length": data.length,

                /** Defino o a prioridade */
                "X-Custom-Header": "ProcessThisImmediately"

            },

            /** Definição do cache */
            cache: 'no-store',

            /** Dados para envio */
            body: data,

        };

        /** Retorno da informação */
        return header;

    }

    /** Listagem de todos os campos */
    serializeForm(form) {

        /** Verifico se o formulario existe */
        if (document.getElementById(form)) {

            /** Obtenho todos os dados do formulario */
            var tempForm = document.getElementById(form);

            /** Obtenho apenas os campos do formulário */
            var tempData = new FormData(tempForm);

            /** Transform os campos do formulário em parâmetros de URL */
            tempData = new URLSearchParams(tempData).toString();

            /** Busco o elemetno desejado */
            var editorElements = document.getElementsByClassName('editor');

            /** Verifica se o editor está ativo */
            if (editorElements.length > 0) {

                /** List todos os editores */
                for (let i = 0; i < editorElements.length; i++) {

                    /** Obtenho os dados do editor */
                    tempData = tempData + '&' + editorElements[i].id + '=' + encodeURIComponent(editorElements[i].innerHTML);

                }

            }

            /** Retorno da informação */
            return tempData;

        }
        else {

            console.log('Não foi possivel localizar o formuláro');

        }

    }

    objectToUrlEncoded(obj) {
        // Função para converter um objeto em uma string codificada no formato de URL

        const urlEncodedData = Object.keys(obj).map(key => {
            // Obtém todas as chaves do objeto e usa o método 'map' para transformar cada chave-valor em uma string codificada

            return encodeURIComponent(key) + '=' + encodeURIComponent(obj[key]);
            // Para cada chave do objeto, codifica tanto a chave quanto o valor usando 'encodeURIComponent'
            // Formata como "chave=valor" para cada par do objeto

        }).join('&');
        // Junta todas as strings codificadas com o caractere '&', criando uma string no formato de parâmetros de URL

        return urlEncodedData;
        // Retorna a string codificada no formato de URL

    }

    /** Função para executar mudanças de páginas */
    send() {


        /** Verifica se o target foi informado */
        if (this._data.response?.target) {

            var isTarget = document.getElementById(this._data.response.target);

            if (!isTarget instanceof HTMLElement) {

                /** Apago possivel mensagem de erros exibidas anteriormente */
                document.getElementById(isTarget).innerHTML = '';
            }

        }

        /** Verifica se o target foi informado */
        if (this._data?.loader_target) {

            /** Apago possivel mensagem de erros exibidas anteriormente */
            document.getElementById(this._data.loader_target.target).innerHTML = '<div class="spinner-border spinner-border m-4" role="status"><span class="visually-hidden">Loading...</span></div>';

        }

        // Verifico se devo exibir o loader
        if (this._data.loader) {

            // Exibição do loader
            this._loader.create();

        }

        try {

            /** Verifico se devo realizar o Serialize */
            if (this._data.request) {

                /** Converto o objeto  */
                this._data.request = this.objectToUrlEncoded(this._data.request) + '.php';

                // Verifico se existe parametros para serem convertidos em url
                if (this._data.params) {

                    this._data.request += '&' + this.objectToUrlEncoded(this._data.params);

                }

            }

            // Verifico se devo realizar serialize do formulário
            if (this._data.form) {

                /** guardo o serialize */
                this._data.request += '&' + this.serializeForm(this._data.form);

            }

            /** Url para envio */
            fetch((this._path !== null ? this._path + '/' : '') + 'router.php', this.setHeader(this._data.request))

                /** Fetch do objeto */
                .then(response => response.json())

                /** Resultado da requisição BEM SUCEDIDA */
                .then((response) => {

                    /** Guardo os dados de envio */
                    this._data.result = response;

                    // Manipulo o resultado da requisição
                    ManageResponse(this._data);

                })

                /** Resultado da requisição MAL SUCEDIDA */
                .catch(error => {

                    console.log(error);

                })

        } catch (error) {

            console.log(error.message);

        } finally {

            // Verifico se devo remover o loader
            if (this._data.loader) {

                window.setTimeout(() => {

                    // Remoção do Loader
                    this._loader.destroy();

                }, 1000)

            }

        }

    }

}