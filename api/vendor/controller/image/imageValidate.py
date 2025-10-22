import base64


class ImageValidate:

    # Inicialização da classe
    def __init__(self, base64_string: str):

        # Armazena o resultado da validação
        self._base64_string = base64_string
        self._message_error = ""
        self._base64_bytes = ""

    # Método de instância (precisa de 'self')
    def is_base64(self) -> bool:

        # Verifica se o base64 foi informado
        if self._base64_string:
            try:
                # Tenta decodificar a string em base64
                self._base64_bytes = base64.b64decode(
                    self._base64_string, validate=True)

                # Opcional: Verifica se a string decodificada pode ser reconvertida sem erros
                if base64.b64encode(self._base64_bytes).decode('utf-8') == self._base64_string:
                    return self._base64_string

                else:
                    self._message_error = "Arquivo base64 inválido"
                    return None

            except Exception as e:

                self._message_error = f"Base64 inválido: {e}"
                return None

        else:
            self._message_error = "O arquivo base64 não foi informado"
            return None

    # Retornar possiveis erros
    def get_errors(self) -> str:
        return self._message_error
