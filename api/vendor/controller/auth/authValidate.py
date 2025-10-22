import re


class AuthValidate:

    # Inicialização da classe
    def __init__(self, auth_email: str, auth_password: str):

        # Controles
        self.auth_email = self.sanitize_input(auth_email)
        self.auth_password = self.sanitize_input(auth_password)
        self.message_error = ""
        self._valid_auth_email = ""
        self._valid_auth_password = ""

    # Efetua a sanitização do parametro informado
    def sanitize_input(self, input: str) -> str:
        # Remove caracteres que podem ser utilizados para SQL Injection
        input_clear = re.sub(r"[;\'\"\\]", "", input)
        # Remove dois hífens consecutivos
        input_clear = re.sub(r"--", "", input_clear)
        return input_clear.strip()

    # Valida o email informado
    def valid_auth_email(self) -> bool:
        padrao_auth_email = r'^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$'

        if not self.auth_email:
            self.message_error += "O e-mail precisa ser informado. "
            return False
        if not re.match(padrao_auth_email, self.auth_email):
            self.message_error += "O email informado é inválido. "
            return False
        return True

    # Valida o password informado
    def valid_auth_password(self) -> bool:

        # Verifica se a senha foi informada
        if not self.auth_password:
            self.message_error += "O password precisa ser informado. "
            return False

        # Verifica se a senha contém pelo menos uma letra (maiúscula ou minúscula)
        has_letters = re.search(r'[A-Za-z]', self.auth_password)

        # Verifica se a senha contém pelo menos um número
        has_numbers = re.search(r'\d', self.auth_password)

        # Verifica se a senha contém pelo menos um caractere especial
        has_special_characters = re.search(
            r'[!@#$%^&*(),.?":{}|<>]', self.auth_password)

        # A senha é válida se tiver pelo menos uma letra, um número e um caractere especial
        if has_letters and has_numbers and has_special_characters:
            return True
        else:
            self.message_error += "A senha deve conter pelo menos uma letra, um número e um caractere especial."
            return False

        return True

    # Efetua a sanitização e validação dos campos
    def valid_auth(self) -> bool:
        self._valid_auth_email = self.valid_auth_email()
        self._valid_auth_password = self.valid_auth_password()

        if not (self._valid_auth_email and self._valid_auth_password):
            self.message_error += "Verifique o e-mail e a senha. "
            return False

        return True

    # Retorna o email sanitizado
    def get_auth_email(self) -> str:
        return str(self.auth_email)

    # Retorna a senha sanitizada

    def get_auth_password(self) -> str:
        return self.auth_password

    # Retornar possiveis erros
    def get_errors(self) -> str:
        return self.message_error
