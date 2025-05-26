
/** Função para remover o html existente e preenchimento de um novo */
function putHtml(target, data) {

    /** Preenchimento da div desejada **/
    $('#' + target).html(data);

}

/** Manipulação de respostas */
function ManageResponse(data) {

    // Carrego as mascaras de campos
    loadMask();

    /** Remove o backdrop anteriormente 
     * criado caso exista */

    if (document.getElementById("modal-backdrop")) {

        /** Carrega o elemento a ser removido */
        this._removeBackdrop = document.getElementById("modal-backdrop");

        /** Remove o elemento */
        this._removeBackdrop.parentNode.removeChild(this._removeBackdrop);
    }

    /** Verifica se o target foi informado */
    if (data.response?.target) {

        /** Apago possivel mensagem de erros exibidas anteriormente */
        document.getElementById(data.response.target).innerHTML = '';
    }

    // if (document.querySelector(".modal-backdrop")) {

    //     /** Carrega o elemento a ser removido */
    //     this._removeBackdrop = document.querySelector(".modal-backdrop");

    //     /** Remove o elemento */
    //     this._removeBackdrop.parentNode.removeChild(this._removeBackdrop);
    // }


    /** Verifico o tipo de resposta retornada */
    switch (data.result.code) {

        /** Erro na execução do backend */
        case 0:


            /** Verifico se devo exbir o erro em modal */
            if (data.result.modal) {

                for (var i = 0; i < (data.result.modal.length); i++) {

                    //** Notificação de erro */
                    new Modal({
                        title: data.result.modal[i].title,
                        data: data.result.modal[i].data,
                        size: data.result.modal[i].size,
                        type: data.result.modal[i].type,
                        procedure: data.result.modal[i].procedure
                    });
                }
            }

            /** Verifico se devo exbir o erro em toast */
            if (data.result.toast) {

                /** Percorro todos os itens informados */
                for (let i = 0; i < data.result.toast.length; i++) {

                    /** Verifico se devo exibir o Toast */
                    new Toast({ create: true, background: data.result.toast[i].background, text: data.result.toast[i].data });

                }

            }

            console.log(data);

            /** Verifico se existe mensagem de erro para ser exibida */
            if (data.result.data) {

                /** Verifica se existem response */
                if (data.response) {

                    /** Preencho o alvo com a mensagem de erro */
                    document.getElementById(data.response.target).innerHTML = `${data.result.data}`;

                } else {

                    new Toast({ create: true, background: 'warning', text: data.result.data });
                }

            }
            break;

        /** Rota executada com sucesso */
        case 100:

            // Verifico se tem alvo para preencher
            if (data.response && data.response.target) {

                /** Preenchimento da div desejada **/
                $('#' + data.response.target).html(data.result.data);

            }
            else {

                /** Preenchimento da div desejada **/
                $('#app').html(data.result.data);

            }

            break;

        /** Action Executado com sucesso */
        case 200:

            /** Verifico se devo exbir o erro em modal */
            if (data.result.modal) {


                /** Percorro todos os itens informados */
                for (var i = 0; i < data.result.modal.length; i++) {

                    //** Notificação de erro */
                    new Modal({ title: data.result.modal[i].title, data: data.result.modal[i].data, size: data.result.modal[i].size, type: data.result.modal[i].type, procedure: data.result.modal[i].procedure });

                }

            }

            /** Verifico se devo exbir a mensagem em toast */
            if (data.result.toast) {

                for (var i = 0; i < (data.result.toast.length); i++) {

                    /** Verifico se devo exibir o Toast */
                    new Toast({ create: true, background: data.result.toast[i].background, text: data.result.toast[i].data });
                }
            }

            /** Verifico se é para remover algum item da tela */
            if (data.result?.remove) {

                /** Verifica se o item a ser removido foi informado */
                if (data.result.remove[0].id) {

                    /** Carrega o elemento a ser removido */
                    var idItem = document.getElementById(data.result.remove[0].id);

                    /** Verifica se o elemento a ser removido existe */
                    if (idItem) {

                        /** Remove o elemento */
                        idItem.parentNode.removeChild(idItem);
                    }
                }

            }


            /** Verifica se é para recarregar o conteúdo na tela */
            if (data.result.redirect) {

                for (var i = 0; i < (data.result.redirect.length); i++) {
                    // Cria uma nova instância da classe Router, passando a URL base como argumento
                    new Request({
                        "request": { "path": data.result.redirect[i].path },
                        "loader": { "type": data.result.redirect[i].type },
                        "response": { "target": data.result.redirect[i].target }
                    })
                }
            }

            /** Verifica se é para efetuar um redirecionamento */
            if (data.result.reload) {

                /** Percorro todos os itens informados */
                for (var i = 0; i < data.result.reload.length; i++) {

                    /** Efetua o redirecionamento */
                    window.location = data.result.reload[i].url;

                }

            }

            /** Verifico se devo realizar funções JavaScript */
            if (data.result.procedure) {

                /** Percorro a lista de funções JavaScript a serem executadas */
                for (var i = 0; i < data.result.procedure.length; i++) {

                    /** Realizo as funções JavaScript */
                    window[data.result.procedure[i].name](data.result.procedure[i].options);

                }

            }

            /** Verifica se existem itens para efetuar o download */
            if (data.result.download) {

                /** Percorro todos os itens informados */
                for (var i = 0; i < data.result.download.length; i++) {

                    console.log(data.result.download[i].path);

                    // Cria um elemento <a> dinamicamente
                    const a = document.createElement('a');
                    a.href = data.result.download[i].path;
                    a.download = data.result.download[i].name; // Define o nome do arquivo que será baixado
                    document.body.appendChild(a); // Adiciona o elemento ao DOM

                    // Simula o clique no link para iniciar o download
                    a.click();

                    // Remove o elemento <a> do DOM após o clique
                    document.body.removeChild(a);

                }

            }

            /** Verifica se  conteúdo 
             * deverá ser carregado em 
             * um alvo especifico */
            if (data.response?.target) {

                /** Preenchimento da div desejada **/
                $('#' + data.response.target).html(data.result.data);

            }
            break;

    }

}