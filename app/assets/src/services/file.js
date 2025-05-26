/** Classe Desejada */
class File {

    /** Método construtor */
    constructor() {

        this._zone = null;
        this._zone_preview = null;

        /** Parâmetros para envio do base64 particionado */
        this._files = [];
        this._hash = null;
        this._preview = null;
        this._size = null;

        /** Recorte de Imagem */
        this._crop = null;
        this._cropper = null;

        /** Parâmetros para controle 
         * da apresentação do arquivo */
        this._indice = null;

        /** Parâmetros para controle 
         * da apresentação do progressbar */
        this._progress = 0;

    }

    /**
     * Configura e retorna o cabeçalho para uma requisição HTTP com configurações específicas para
     * o método POST e parâmetros no formato de URL.
     *
     * @param {string} data Os dados que serão enviados no corpo da requisição.
     * @returns {Object} Um objeto representando a configuração do cabeçalho da requisição, incluindo
     * método, modo, cabeçalhos específicos, cache e o corpo da requisição.
     *
     * Esta função é útil para preparar requisições que exigem o envio de dados com
     * um cabeçalho específico e controlado, especialmente útil em contextos onde é
     * necessária a interação com APIs que aceitam dados via POST com conteúdo URL-encoded.
     *
     * Exemplo de uso:
     *   let postData = "key1=value1&key2=value2";
     *   let headersConfig = setHeader(postData);
     *   fetch('https://example.com/api', headersConfig)
     *     .then(response => response.json())
     *     .then(data => console.log(data))
     *     .catch(error => console.error('Error:', error));
     */
    setHeader(data) {

        var header = {

            method: 'post',      // Método HTTP para a requisição.
            mode: 'cors',        // Política de CORS para a requisição.
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",  // Tipo de conteúdo esperado pelo servidor.
                "Content-Length": data.length,                        // Comprimento do conteúdo em caracteres.
                "X-Custom-Header": "ProcessThisImmediately"           // Header customizado para priorização da requisição.
            },
            cache: 'no-store',  // Política de cache, evitando armazenar qualquer resposta.
            body: data          // Dados que serão enviados no corpo da requisição.

        };

        return header;

    }

    /**
     * Serializa todos os campos de um formulário especificado para uma string no formato de parâmetros de URL.
     *
     * @param {string} form O ID do formulário no documento HTML que será serializado.
     * @returns {string} Uma string contendo todos os campos do formulário serializados como parâmetros de URL.
     *
     * Este método facilita a obtenção e uso de dados de formulário para envio via URL, por exemplo,
     * em solicitações de API onde o conteúdo é esperado em formato de query string.
     *
     * Exemplo de uso:
     *   var formDataString = serializeForm('meuFormulario');
     *   console.log(formDataString);  // Saída: "campo1=valor1&campo2=valor2&campo3=valor3"
     */
    serializeForm(form) {

        // Obtenção do elemento do formulário pelo ID fornecido.
        var tempForm = document.getElementById(form);

        // Criação de um objeto FormData a partir do elemento do formulário.
        var tempData = new FormData(tempForm);

        // Conversão do objeto FormData em uma string de parâmetros de URL.
        tempData = new URLSearchParams(tempData).toString();

        // Retorna a string de parâmetros, pronta para ser usada em URLs ou requisições HTTP.
        return tempData;

    }

    /**
     * Remove uma imagem com base no ID fornecido, atualizando a interface e a array de envio.
     * @param {number} target - O ID da imagem a ser removida.
     */
    remove(target) {

        /** Localiza o elemento desejado e realiza a remoção */
        document.getElementById(target).remove();

        /** Remove o item da array de base64 */
        this._files.splice(target, 1);

        /** Verifico se devo remover o botão de salvar e habilitar o formulário */
        if (this._files.length === 0) {

            /** Removo o corpo da listagem */
            document.getElementById('ItemListWrapper').remove();

            /** Removo o Botão de salvar */
            document.getElementById('ButtonSalvar').remove();

            /** Habilito o formulário de upload para visualização */
            RemoveClass('dropContainer', 'd-none');

        }

    }

    /**
     * Remove o elemento HTML com base no ID fornecido.
     * @param {string} target - O ID do elemento HTML a ser removido.
     */
    removeHtml(target) {

        /** Obtém o elemento HTML com o ID fornecido */
        var html = document.getElementById(target);

        /** Verifica se o elemento existe antes de tentar removê-lo */
        if (html) {

            /** Remove o elemento HTML */
            html.remove();

        }

    }

    /** Definição de como sera exibido o arquivo html */
    view(data) {

        /** Verifico o tipo de exibição devo realizar */
        switch (this._preview) {

            /** Visualização em Lista comum */
            case 1:

                /** Verifico se já existe a estrutura inicial */
                if (!document.getElementById('ItemListWrapper')) {

                    /** Preenchimento do html no elemento desejado */
                    this._zone_preview.innerHTML = `<div class="card" id="ItemListWrapper">
                                                       <ul class="list-group list-group-flush" id="ItemListBody"></ul>
                                                    </div>
                                                    <button type="button" class="btn btn-primary w-100 mt-1" id="ButtonSalvar" onclick="_file.before()">
                                                        <i class="bi bi-check me-1"></i>Enviar Arquivos
                                                    </button>`;

                }

                /** Obtenho o corpo da listagem */
                document.getElementById('ItemListBody').innerHTML += `<li class="list-group-item" id="item_${data.indice}">
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="flex-shrink-0">
                                                                                        <img src="assets/images/default/files/${this.getExtension(data.name)}.png" width="40px">
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-2">
                                                                                        <div class="fs-6 mb-0 fw-semibold">
                                                                                            <span id="status_${data.indice}"></span>
                                                                                            ${data.name}
                                                                                        </div>
                                                                                        <div class="fs-6 mt-0" id="complement_${data.indice}">
                                                                                            ${this.getSize(data.size)}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-shrink-0 text-end" id="col3_${data.indice}">
                                                                                        <button class="btn btn-danger btn-sm" type="button" onclick="_file.remove('item_${data.indice}')" id="btn_${data.indice}">
                                                                                            <i class="bi bi-trash"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                               </li>`;
                break;

            /** Visualização em Lista perfil */
            case 2:

                /** Preenchimento do html no elemento desejado */
                this._zone_preview.innerHTML = `<img id="FileImagePreview" src="#" class="img-fluid rounded border">
                                                <div id="complement_${data.indice}"></div>
                                                <button type="button" class="btn btn-primary w-100 mt-1" id="ButtonSalvar" onclick="_file.before()">
                                                   <i class="bi bi-check me-1"></i>Salvar
                                                </button>`

                /** Obtenho o campo onde irá mostrar a imagem */
                document.getElementById('FileImagePreview').src = data.chunks;

                /** Habilito o recorte de imagem */
                new Croppr('#FileImagePreview', {

                    aspectRatio: 1, // Define a proporção de aspecto como 16:9
                    startSize: [10, 10, '%'],
                    onCropMove: (data) => { this._crop = data; }

                });

                break;

        }

    }

    /**
     * Método para atualizar ou criar uma barra de progresso em um determinado alvo.
     * @param {string} target - O identificador do alvo onde a barra de progresso será atualizada ou criada.
     */
    progress(target) {

        /** Verifica se já existe a barra de progresso */
        if (document.getElementById('progress_' + target)) {

            /** Obtém o elemento que representa a barra de progresso */
            var size = document.getElementById('size_' + target);

            /** Atualiza a largura da barra de progresso com base no tamanho atual */
            size.style.width = this._size + "%";

        } else {

            /** Obtém o elemento onde a barra de progresso será inserida */
            var _target = document.getElementById('complement_' + target);

            /** Estrutura HTML para a barra de progresso */
            var html = `<div class="progress mt-2" id="progress_${target}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="height:5px">
                                    <div class="progress-bar" style="width: 0%" id="size_${target}"></div>
                                </div>`;

            /** Insere a barra de progresso na posição desejada */
            _target.innerHTML = html;

        }

    }

    showForm() {

        /** Reinicio os parâmetros da classe */
        this._files = [];
        this._hash = null;
        this._crop = null;
        this._preview = null;
        this._size = null;
        this._indice = null;
        this._progress = 0;

        /** Limpo a visualização anterior */
        this._zone_preview.remove();

        /** Habilito a visualização do formulário */
        RemoveClass('dropContainer', 'd-none');

    }

    /**
     * Função que converte um arquivo para sua representação em base64.
     * @param {File} inputFile - O arquivo a ser convertido.
     * @returns {Promise<string>} - Uma promessa que resolve com a representação do arquivo em base64, ou rejeita com uma mensagem de erro.
     */
    getFileBase64(inputFile) {

        return new Promise((resolve, reject) => {

            // Cria um novo objeto FileReader
            var reader = new FileReader();

            // Lê o arquivo como uma URL de dados (base64)
            reader.readAsDataURL(inputFile);

            // Define o callback para quando a leitura for concluída com sucesso
            reader.onload = function () {

                // Obtém a representação base64 do arquivo
                var base64 = reader.result;

                // Resolve a promessa com a representação base64 do arquivo
                resolve(base64);

            };

            // Define o callback para quando ocorrer um erro durante a leitura
            reader.onerror = function (error) {

                // Rejeita a promessa com uma mensagem de erro
                reject('Erro ao ler o arquivo: ' + error);

            };

        });

    }

    /**
     * Função que realiza a separação de uma string em partes menores (chunks) e retorna um array contendo essas partes.
     * @param {string} inputString - A string a ser dividida em chunks.
     * @param {number} chunkSize - O tamanho desejado de cada chunk em termos de caracteres.
     * @returns {Array} - Um array contendo os chunks da string.
     */
    getChunk(inputString, chunkSize) {

        // Inicializa um array para armazenar os chunks resultantes.
        const chunks = [];

        // Loop através da string de entrada, com incremento pelo tamanho do chunk.
        for (let i = 0; i < inputString.length; i += chunkSize) {

            // Obtém uma parte (chunk) da string com base no tamanho especificado.
            const chunk = inputString.substring(i, i + chunkSize);

            // Adiciona o chunk ao array de chunks.
            chunks.push(chunk);

        }

        // Retorna o array contendo os chunks da string.
        return chunks;

    }

    /**
     * Função que recebe um nome de arquivo e retorna a extensão do arquivo.
     * @param {string} data - O nome do arquivo ou caminho completo do arquivo.
     * @returns {string|null} - A extensão do arquivo, ou null se nenhum ponto for encontrado no nome do arquivo.
     */
    getExtension(data) {

        // Verifica se o nome do arquivo possui uma extensão
        if (data.lastIndexOf('.') !== -1) {

            // Obtém a posição do último ponto no nome do arquivo
            var point = data.lastIndexOf('.');

            // Extrai a extensão usando substr
            var extension = data.substr(point + 1);

            //Retorno o tipo da extensão
            return extension;

        }

        // Se não houver extensão, retorna null
        return null;

    }

    /**
     * Converte o tamanho de um conjunto de dados em um formato legível por humanos.
     * @param {number} dados - O tamanho dos dados em bytes.
     * @returns {string} - O formato legível por humanos do tamanho.
     */
    getSize(data) {

        // Se o tamanho dos data for 0, retorna '0 Bytes'
        if (data === 0) return '0 Bytes';

        // Define as unidades para conversão de tamanho
        const units = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        // Calcula a scale do tamanho
        const scale = Math.floor(Math.log(data) / Math.log(1024));

        // Converte o tamanho para a unidade apropriada e formata-o
        return parseFloat((data / Math.pow(1024, scale)).toFixed(2)) + ' ' + units[scale];

    }

    /**
     * Prepara a visualização dos arquivos selecionados e os armazena para envio posterior.
     * @param {string} target - O ID do elemento que contém os arquivos selecionados.
     * @returns {void}
     */
    async prepare(target) {

        /** Oculta o formulário de envio */
        AddClass('dropContainer', 'd-none');

        /** Obtém os arquivos selecionados no campo input de acordo com o TARGET */
        var _files = document.getElementById(target);

        /** Obtém o tipo de visualização que será usada */
        this._preview = parseInt(_files.dataset.mysupportFilePreview);

        /** Obtenho a zona do arquivo */
        this._zone = document.getElementById(_files.dataset.mysupportFileZone);

        /** Crio o espaço para visualização do arquivos */
        var FilesPreview = document.createElement('div');

        /** Defino o Id do espaço */
        FilesPreview.id = 'FilesPreview';

        /** Insiro a zona de visualização na zona de upload */
        this._zone.appendChild(FilesPreview);

        /** Localizo a zona de visualização de arquivo */
        this._zone_preview = document.getElementById('FilesPreview');

        /** Itera sobre todos os elementos da array de arquivos selecionados */
        for (let i = 0; i < _files.files.length; i++) {

            /** Variável local temporária para armazenar os detalhes do arquivo */
            var temp = {};

            /** Guarda o índice do arquivo */
            temp.indice = i;

            /** Obtém o nome do arquivo */
            temp.name = _files.files[i].name;

            /** Obtém o tamanho do arquivo */
            temp.size = _files.files[i].size;

            /** Separo o base64 de acordo com a visualização */
            var base64 = await this.getFileBase64(_files.files[i]);

            /** Guardo o base64 original */
            temp.chunks = base64;

            /** Realiza a montagem da visualização dos arquivos na tela */
            this.view(temp);

            /** Removo apenas o cabeçalho do base64 */
            base64 = base64.split(',')[1];

            /** Obtém e armazena as partes do arquivo em chunks */
            temp.chunks = this.getChunk(base64, (1024 * 1024));

            /** Armazena as informações do arquivo na classe */
            this._files.push(temp);

        }

        /** Reinicia o campo file */
        _files.value = '';

    }

    /**
     * Função assíncrona para enviar um arquivo 
     * por meio de uma requisição assíncrona.
     *
     * @param {FormData} form - Objeto FormData contendo 
     * os dados do arquivo a ser enviado.
     * @returns {void}
     */
    send(form) {

        return new Promise((resolve, reject) => {

            /** Faz a solicitação usando fetch */
            fetch('router.php', this.setHeader(form))

                /** Fetch do objeto */
                .then(response => response.json())

                .then(response => {

                    /** Converte a resposta em JSON 
                     * e resolve a Promise com os dados */
                    return response;
                })

                .then(data => {

                    /** Resolve a Promise com os dados obtidos */
                    resolve(data);
                })

                .catch(error => {

                    /** Rejeita a Promise com o erro 
                     * ocorrido durante a solicitação */
                    reject(error);
                });

        });

    }

    async finally(data) {

        /** Verifico se devo realizar o merge do arquivo */
        if (data.merge) {

            try {

                /** Atualizo a barra de progresso */
                this._size = 100;

                /** Atualizo a barra de progresso */
                this.progress(this._indice);

                // Obtém os valores dos campos do formulário em formato de string.
                var form = this.serializeForm('FilesFormHeader');

                // Adiciona os dados específicos da imagem à string de dados.
                form += '&name=' + this._files[this._indice].name;

                // Adiciona o conteúdo da parte atual do arquivo à string de dados.
                form += '&hash=' + this._hash;

                // Adiciona o conteúdo da parte atual do arquivo à string de dados.
                form += '&crop=' + JSON.stringify(this._crop);
                form += '&log_register_id=' + data.file_id;
                form += '&file_id=' + data.file_id;

                /** Extrai os dados JSON da resposta */
                data = await this.send(form);

                /** Verfico se encerrou o envio */
                if (data.end && this._preview === 1) {

                    /** Remove a barra de progresso */
                    document.getElementById('progress_' + (this._progress)).remove();

                    /** Habilito a visualização do arquivo */
                    document.getElementById('status_' + (this._progress)).innerHTML = `<button class="btn btn-success btn-sm" id="btnViewFile` + this._progress + `" onclick='new Request({"request" : {"path" : "view/files/files_preview"}, 
                                                                                                                                                                                           "loader" : {"type" : 1, "target" : "btnViewFile` + this._progress + `"},
                                                                                                                                                                                           "params" : {"log_register_id" : ${data.file_id}, 
                                                                                                                                                                                           "file_id" : ${data.file_id}}})'>Visualizar Arquivo </button>`;

                    /** Incrementa o contador do progressbar */
                    this._progress++;

                }

                /** Verifica se todos os itens foram enviados
                 * Caso tenha sido enviados, informo
                 */
                if (this._files.length == this._progress) {

                    new Toast({ create: true, background: 'success', text: '<i class="bi bi-check me-1"></i>Arquivos enviados com sucesso!' });
                }


                /** Informa possiveis erros  */
            } catch (error) {

                console.log(error);
            }
        }
    }

    /**
     * Função que prepara a requisição para envio, 
     * dividindo o arquivo em partes e enviando cada parte.
     */
    async before() {

        /** Removo o html desejado */
        this.removeHtml('ButtonSalvar');

        /** Percorre todos os arquivos anteriormente selecionadas */
        for (let i = 0; i < this._files.length; i++) {

            /** Removo o html desejado */
            this.removeHtml('col3_' + i);

        }

        /** Percorre todos os arquivos anteriormente selecionadas e salvos em array */
        for (let i = 0; i < this._files.length; i++) {

            /** Guarda o índice do arquivo que está sendo enviado */
            this._indice = i;

            /** Obtém o momento atual */
            const dateTime = new Date();

            /** Cria um HASH localizador concatenando tamanho do arquivo, hora, minutos, segundos e milissegundos */
            this._hash = this._files[i].size + dateTime.getHours() + dateTime.getMinutes() + dateTime.getSeconds() + dateTime.getMilliseconds();

            /** Para cada chunk gerado, realiza o envio do mesmo */
            for (let j = 0; j < this._files[i].chunks.length; j++) {

                /** Calculo o progresso */
                this._size = (j / this._files[i].chunks.length) * 100;

                /** Formato os numeros */
                this._size = this._size.toFixed(2);

                /** Atualizo a barra de progresso */
                this.progress(this._indice);

                // Adiciona o número da parte atual do arquivo à string de dados.
                var form = 'path=action/files/files_save_chunk.php';

                // Adiciona os dados específicos da imagem à string de dados.
                form += '&name=' + this._files[i].name;

                // Adiciona o número da parte atual do arquivo à string de dados.
                form += '&chunk_part=' + (j + 1);

                // Adiciona o tamanho total do arquivo à string de dados.
                form += '&chunk_size=' + this._files[i].chunks.length;

                // Adiciona o conteúdo da parte atual do arquivo à string de dados.
                form += '&base64=' + this._files[i].chunks[j];

                // Adiciona o conteúdo da parte atual do arquivo à string de dados.
                form += '&hash=' + this._hash;

                // Envia a parte do arquivo.
                this.finally(await this.send(form));

            }

        }

        /** Preencho os itens no corpo da lista */
        this._zone_preview.innerHTML += `<button type="button" class="btn btn-primary w-100 mt-1" id="ButtonFileMore" onclick="_file.showForm()">
                                            <i class="bi bi-plus me-1"></i>Enviar mais arquivos
                                        </button>`;

    }

}

/** Habilitar ou desabilitar 
 * o tipo de arquivo */
function enableOrDisable() {
    const radioButtons = document.querySelectorAll('input[type="radio"]');
    const fileInput = document.getElementById("file");

    // Remove ambos os atributos antes de aplicar o correto
    fileInput.removeAttribute("multiple");
    fileInput.removeAttribute("webkitdirectory");

    radioButtons.forEach(radio => {
        if (radio.checked) {
            if (radio.value === "files") {
                fileInput.setAttribute("multiple", "");
            } else if (radio.value === "folders") {
                fileInput.setAttribute("webkitdirectory", "");
            }
        }
    });
}

