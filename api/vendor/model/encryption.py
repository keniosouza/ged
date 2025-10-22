from cryptography.fernet import Fernet


class Encryption:
    def __init__(self, encrypted_or_decripted_text: str):

        self._encrypted_or_decripted_text = encrypted_or_decripted_text
        self._key = 'QkMv8tyCrn9dKJ5z-rPMe1xDch6NYVqs6tWV2ZtqbO0='
        self._fernet = Fernet(self._key)
        self.message_error = ""

    # Efetua a criptografia de uma string, a partir de uma chave informada
    def encrypt(self) -> str:

        try:
            self._encrypted_or_decripted_text = self._apply_encryption(
                self._encrypted_or_decripted_text)
            return self._encrypted_or_decripted_text

        except Exception as e:
            self.message_error = f"Falha na criptografia: {e}"

    # Efetua a descriptografia de uma string
    def decrypt(self) -> str:

        try:

            self._encrypted_or_decripted_text = self._apply_decryption(
                self._encrypted_or_decripted_text)

        except Exception as e:
            self.message_error = f"Falha na descriptografia: {e}"

    # Aplica a criptografia
    def _apply_encryption(self, decripted_text):
        return self._fernet.encrypt(decripted_text.encode())

    # Aplica a descriptografia
    def _apply_decryption(self, encrypted_text):
        return self._fernet.decrypt(encrypted_text).decode()

    def _return_encrypted_or_decripted_text(self):
        return self._encrypted_or_decripted_text

    # Retornar possiveis erros
    def get_errors(self) -> str:
        return self.message_error
