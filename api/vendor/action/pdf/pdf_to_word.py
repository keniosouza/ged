import base64
import io
import os
from pdf2docx import Converter


class PdfToWord:

    def __init__(self, base64_string: str):
        # Armazena a string Base64 em uma variável
        self._base64_string = base64_string

        # Controles
        self._base64 = ""
        self._pdf_temp_file = None
        self._word_temp_file = None
        self._message_error = ""

        # Verifica se o Base64 foi informado
        if self._base64_string:
            try:

                # Decodifica a string Base64 para bytes
                self._base64_bytes = base64.b64decode(self._base64_string)

                # Criar arquivo temporário para o PDF
                self._pdf_temp_file = "temp_pdf_file.pdf"
                with open(self._pdf_temp_file, "wb") as f:
                    f.write(self._base64_bytes)

                # Criar arquivo temporário para o Word
                self._word_temp_file = "temp_word_file.docx"

                # Converter o PDF salvo temporariamente para Word
                cv = Converter(self._pdf_temp_file)
                cv.convert(self._word_temp_file, start=0, end=None)
                cv.close()

                # Ler o arquivo Word temporário e convertê-lo em base64
                with open(self._word_temp_file, "rb") as word_file:
                    word_content = word_file.read()
                    self._base64 = base64.b64encode(
                        word_content).decode('utf-8')

            except Exception as e:
                self._message_error = f"Erro ao processar o pdf: {e}"

            finally:
                # Remover arquivos temporários, se eles existirem
                if self._pdf_temp_file and os.path.exists(self._pdf_temp_file):
                    os.remove(self._pdf_temp_file)
                if self._word_temp_file and os.path.exists(self._word_temp_file):
                    os.remove(self._word_temp_file)

        else:
            self._message_error = "Nenhum arquivo Base64 informado"

    # Retorna o Base64 do arquivo Word gerado
    def get_base64(self) -> str:
        return self._base64

    # Retorna possíveis erros
    def get_errors(self) -> str:
        return self._message_error
