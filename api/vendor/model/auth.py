from vendor.model.db_connection import MySQLConnection
from cryptography.fernet import Fernet


class Auth:

    # Inicialização da classe
    def __init__(self, auth_email: str, auth_password: str):

        # Instância da classe de conexão com o banco de dados
        self.db = MySQLConnection()

        # Controles
        self._key = 'QkMv8tyCrn9dKJ5z-rPMe1xDch6NYVqs6tWV2ZtqbO0='
        self._fernet = Fernet(self._key)
        self.auth_email = auth_email
        self.auth_password = auth_password
        self.db_password = ""
        self.message_error = ""
        self.new_key = ""
        self._auth_token_hash = ""

    # Aplica a descriptografia
    def _apply_decryption(self, encrypted_text):
        return self._fernet.decrypt(encrypted_text).decode()

    def _search(self) -> str:

        # Verifica se os parametros de consulta foram informados
        if self.auth_email and self.auth_password:

            # Efetua a conexão com o banco de dados
            cursor = self.db.get_cursor()

            # Consulta pelo usuário
            query = "SELECT * FROM auth WHERE auth_email = %s"

            # Executa a consulta
            cursor.execute(query, (self.auth_email,))

            # Busca o registro
            row = cursor.fetchone()

            # Verifica se encontrou algum registro
            if row:

                # Verifica se a consulta retornou um ID válido
                if row[0] > 0:

                    # Verifica se a senha informada é a mesma criptografada no banco de dados

                    # Descriptografa a senha contida no banco de dados
                    self.db_password = self._apply_decryption(row[5])

                    # Verifica se as senhas são iguais
                    if self.auth_password == self.db_password:

                        # Gera a chave para gerar o token de acesso
                        self.new_key = Fernet.generate_key()

                        # Verifica se o token foi gerado
                        if self.new_key:

                            try:

                                # Armazena o token para verificação futura
                                query = "INSERT INTO auth_token (auth_id, auth_token_hash) VALUES (%s, %s)"
                                cursor.execute(
                                    query, (row[0], self.new_key))

                                # Verifica se a transação foi bem-sucedida (se pelo menos uma linha foi afetada)
                                if cursor.rowcount > 0:
                                    self.db.commit()  # Confirma a transação
                                else:
                                    self.db.rollback()  # Desfaz a transação
                                    self.message_error += "Não foi possível gerar o token de acesso. "
                                    return None

                            except Exception as e:
                                # Em caso de erro, desfaz a transação e loga o erro
                                self.db.rollback()
                                self.message_error += f"Erro durante a transação: {
                                    e}"
                                return None

                        else:
                            self.message_error += "Não foi possível gerar o token de acesso. "
                            return None

                    else:
                        self.message_error += "O passowrd informado é inválido. "
                        return None

            else:
                self.message_error += "Registro não encontrado. "
                return None

        else:
            self.message_error += "Nenhum e-mail informado. "
            return None

    # Consulta o token informado
    def check_token(self, token) -> bool:

        self._auth_token_hash = token

        # Verifica se o token foi informado
        if self._auth_token_hash:

            # Consulta o token informado
            # Efetua a conexão com o banco de dados
            cursor = self.db.get_cursor()

            # Consulta pelo usuário
            query = "SELECT * FROM auth_token WHERE auth_token_date >= DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND auth_token_hash = %s;"

            # Executa a consulta
            cursor.execute(query, (self._auth_token_hash,))

            # Busca o registro
            row = cursor.fetchone()

            # Verifica se encontrou algum registro
            if row:

                # Verifica se a consulta retornou um ID válido
                if row[0] > 0:

                    # Verifica se o token informado é válido
                    if row[2] == self._auth_token_hash:
                        return True

                    else:
                        self.message_error += "Token não encontrado. "
                        return None

                else:
                    self.message_error += "O token expirou. "
                    return None

            else:
                self.message_error += "O token expirou. "
                return None

        else:
            self.message_error += "Nenhum token informado. "
            return None

    # Retornar a chave gerada
    def get_new_key(self) -> str:
        return self.new_key

    # Retornar possiveis erros
    def get_errors(self) -> str:
        return self.message_error
