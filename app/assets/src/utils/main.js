function changeTheme() {
    const bodyElement = document.querySelector('body');

    // Verifica o tema atual e alterna entre 'dark' e 'light'
    const currentTheme = bodyElement.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    bodyElement.setAttribute('data-bs-theme', newTheme);

    // Armazena o tema na sessionStorage
    sessionStorage.setItem('theme', newTheme);
}

function GetConfig() {

    /** Busco o arquivo desejado */
    fetch('./app/config/config.json')

        /** Converto os dados para json */
        .then(response => response.json())

        /** Manipulo os dados */
        .then(response => {

            /** Retorno da informação */
            return response;

        })

}

function HideModal(data) {

    // Seleciona todos os elementos com a classe 'modal' que estão visíveis
    const activeModals = document.querySelectorAll('.modal.show');

    // Itera sobre a NodeList de modais ativos
    activeModals.forEach(modal => {

        // Utiliza o método Bootstrap para esconder cada modal
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.hide();

    });

}


function addInputModulesAclsForm() {

    /** Id aleatorio */
    let key = Math.random();

    /** Defino a estrutura HTML */
    let html = '<div class="col-md-3 animate slideIn">';
    html += '	<div class="form-group">';
    html += '		<label for="permission' + key + '">';
    html += '			Nome permissão';
    html += '		</label>';
    html += '		<input id="permission' + key + '" type="text" class="form-control form-control-solid" name="permission[]">';
    html += '	</div>';
    html += '</div>';

    /** Preencho o HTML dentro da DIV desejad **/
    $('.row-dynamic-input').append(html);

    /** Defino o foco */
    $('permission' + key).focus();

}

/**
 * Adiciona uma classe CSS a um elemento HTML especificado pelo seu ID.
 * @param {string} tag - O ID do elemento HTML ao qual adicionar a classe CSS.
 * @param {string} classCss - A classe CSS a ser adicionada ao elemento.
 * @returns {void}
 */
function AddClass(tag, classCss) {

    console.log(tag);

    /**
     * Obtém o elemento HTML com o ID especificado.
     * @type {HTMLElement} - Elemento HTML alvo.
     */
    var anchorElements = document.getElementById(tag);

    // Adiciona a classe CSS ao elemento HTML.
    anchorElements.classList.add(classCss);

}

/**
 * Remove uma classe CSS de um elemento HTML especificado pelo seu ID.
 * @param {string} tag - O ID do elemento HTML do qual remover a classe CSS.
 * @param {string} classCss - A classe CSS a ser removida do elemento.
 * @returns {void}
 */
function RemoveClass(tag, classCss) {

    /**
     * Obtém o elemento HTML com o ID especificado.
     * @type {HTMLElement} - Elemento HTML alvo.
     */
    var anchorElements = document.getElementById(tag);

    // Remove a classe CSS do elemento HTML.
    anchorElements.classList.remove(classCss);

}

function ChangeContent(options) {

    var element = document.getElementById(options.target);

    element.innerHTML = options.data;

}

function gerarNumeroAleatorioInclusivo(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}

function loadMask() {

    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.phone').mask('0000-0000');
    $('.phone_with_ddd').mask('(00) 00000-0000');
    $('.phone_us').mask('(000) 000-0000');
    $('.mixed').mask('AAA 000-S0S');
    $('.cpf').mask('000.000.000-00', { reverse: true });
    $('.cnpj').mask('00.000.000/0000-00', { reverse: true });
    $('.money').mask('000.000.000.000.000,00', { reverse: true });
    $('.money2').mask("#.##0,00", { reverse: true });
    $(".number").keypress(checkNumber);
    $('.price').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.',
        //limit: 12,
        centsLimit: 2
    });
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', { reverse: true });
    $('.clear-if-not-match').mask("00/00/0000", { clearIfNotMatch: true });
    $('.placeholder').mask("00/00/0000", { placeholder: "__/__/____" });
    $('.fallback').mask("00r00r0000", {
        translation: {
            'r': {
                pattern: /[\/]/,
                fallback: '/'
            },
            placeholder: "__/__/____"
        }
    });
    $('.selectonfocus').mask("00/00/0000", { selectOnFocus: true });

}

/** Somente números */
function checkNumber(e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
}

/** Habilita e desabilita campos informados a partir de campo select */
function enabledInput(inputSelect, inputEnabled) {

    $(inputSelect).change(function () {

        if ($(inputSelect).val() === 'S') {

            $(inputEnabled).prop("disabled", false);
            $(inputEnabled).focus();

        } else {

            $(inputEnabled).prop("disabled", true);
        }
    });
}

/** Remove um item pelo ID */
function removeItem(idItem) {

    /** Carrega o elemento a ser removido */
    var id = document.getElementById(idItem);

    /** Remove o elemento */
    id.parentNode.removeChild(id);
}

/** Exibe um json  */
function viewJson(title, jsonData) {

    /** Inicio da tabela */
    var table = `<table class="table table-sm">`;
    table += `<thead>`;
    table += `<tr>`;

    /** Lista os itens do json */
    Object.keys(jsonData).forEach(key => {

        switch (`${key}`) {

            case 'name': table += `  <th scope="col" class="text-center">Nome</th>`; break;
            case 'quantity': table += `  <th scope="col" class="text-center">Caracteres</th>`; break;
            case 'format': table += `  <th scope="col" class="text-center">Formato</th>`; break;
            case 'required': table += `  <th scope="col" class="text-center">Requerido</th>`; break;

        }

    });

    table += `</tr>`;

    table += `</thead>`;
    table += `<tbody>`;
    table += `<tr>`;

    /** Lista os itens do json */
    Object.keys(jsonData).forEach(key => {


        if ((`${key}` == 'name') || (`${key}` == 'quantity')) {

            table += `  <td scope="col" class="text-center"> ` + `${jsonData[key]}` + ` </td>`;

        } else if (`${key}` == 'format') {

            if (parseInt(`${jsonData[key]}`) == 1) {

                table += `  <td scope="col" class="text-center"> Texto </td>`;
            }

            else if (parseInt(`${jsonData[key]}`) == 2) {

                table += `  <td scope="col" class="text-center"> Número </td>`;
            }


            else if (parseInt(`${jsonData[key]}`) == 3) {

                table += `  <td scope="col" class="text-center"> Data </td>`;
            }

            else if (parseInt(`${jsonData[key]}`) == 4) {

                table += `  <td scope="col" class="text-center"> Monetário </td>`;
            }

            else if (parseInt(`${jsonData[key]}`) == 5) {

                table += `  <td scope="col" class="text-center"> CPF </td>`;
            }

            else if (parseInt(`${jsonData[key]}`) == 6) {

                table += `  <td scope="col" class="text-center"> CNPJ </td>`;
            }

            else if (parseInt(`${jsonData[key]}`) == 7) {

                table += `  <td scope="col" class="text-center"> CEP </td>`;
            }

            else if (parseInt(`${jsonData[key]}`) == 8) {

                table += `  <td scope="col" class="text-center"> Celular </td>`;
            }

            else if (parseInt(`${jsonData[key]}`) == 9) {

                table += `  <td scope="col" class="text-center"> E-mail </td>`;
            }

            else if (parseInt(`${jsonData[key]}`) == 10) {

                table += `  <td scope="col" class="text-center"> RG </td>`;
            }

        } else if (`${key}` == 'required') {


            if (parseInt(`${jsonData[key]}`) == 1) {

                table += `  <td scope="col" class="text-center"> Sim </td>`;
            } else {
                table += `  <td scope="col" class="text-center"> Não </td>`;
            }

        }

    });
    table += `</tr>`;
    table += `</tbody>`;
    table += `</table>`;

    new Modal({ title: title, data: table, size: 'lg', type: null, procedure: null });

}

/** Verifica campos obrigatórios antes de enviar o formulário */
function validateForm(form, jsonData, dir = null) {

    /** Controles */
    var err = 0;

    /** Converte a string com o dados para o formato Json */
    var data = JSON.parse(jsonData);

    /** Percorre o formulário por campos obrigatórios */
    $('[data-required]', form).each(function () {

        if ($(this).attr('data-required') == 'S') {

            /** Verifica se o campo obrigatório possui valor informado */
            if ($($(this).attr('data-required', 'S')).val() == '') {

                /** Habilita o tooltip */
                $($(this).attr('data-required', 'S')).tooltip('show');

                /** Aplica a borda vermelha no input */
                $($(this).attr('data-required', 'S')).addClass("border border-danger");

                /** Contabiliza os campos obrigatórios vazios */
                err++;
            }
        }
    });

    /** Verifica se nao existem erros, 
     * caso não existam erros, envio o formulário */
    if (err == 0) {

        /** Envia a requisição */
        new Request({
            "request": { "path": data.request.path },
            "loader": { "type": data.loader.type, "target": data.loader.target },
            "response": { "target": data.response.target },
            "form": data.form
        }, dir)

    } else {

        /** Remove as bordas vermelhas a partir de 5 segundos e oculta o tooltip */
        setTimeout(function () {

            /** Percorre o formulário por campos obrigatórios */
            $('[data-required]').each(function () {

                /** Remove os estilos de obrigatório */
                $($(this).attr('data-required', 'S')).removeClass("border border-danger");

                /** Oculta o tooltip */
                $($(this).attr('data-required', 'S')).tooltip('hide');

            });

        }, 2000);
    }
}