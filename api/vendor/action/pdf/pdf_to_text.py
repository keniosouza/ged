import base64
import io
import pdfplumber
import pytesseract
from PIL import Image


class PdfToText:

    def __init__(self, base64_string: str):
        # Armazena a string Base64 em uma variável
        self._base64_string = base64_string

        # Caminho absoluto do Tesseract
        self._tesseract = r"C:\Program Files\Tesseract-OCR\tesseract.exe"

        # Controles
        self._extracted_text = ""  # Armazena o texto extraído do PDF
        self._message_error = ""

        # Verifica se o Base64 foi informado
        if self._base64_string:
            try:
                # Decodifica a string Base64 para bytes
                self._base64_bytes = base64.b64decode(self._base64_string)

                # Usar BytesIO para manter o PDF em memória
                pdf_stream = io.BytesIO(self._base64_bytes)

                # Abre o PDF diretamente da stream de bytes
                with pdfplumber.open(pdf_stream) as pdf:
                    # Itera sobre todas as páginas do PDF e extrai o texto
                    for page in pdf.pages:
                        extracted_page_text = page.extract_text()

                        # Se o texto extraído da página for None, tenta OCR
                        if extracted_page_text:
                            self._extracted_text += extracted_page_text
                        else:
                            # Converte a página em uma imagem para o OCR
                            image = page.to_image(resolution=300)
                            pil_image = image.original  # Pega a imagem PIL

                            # Processa a imagem
                            pytesseract.pytesseract.tesseract_cmd = self._tesseract

                            # Extrai o texto da imagem usando Tesseract
                            ocr_text = pytesseract.image_to_string(pil_image)
                            self._extracted_text += ocr_text

                # Verifica se o texto foi devidamente extraído
                if not self._extracted_text.strip():  # Verifica se está vazio
                    self._message_error = "Não foi possível extrair texto do PDF, mesmo com OCR."

            except Exception as e:
                self._message_error = f"Erro ao processar o PDF: {e}"

        else:
            self._message_error = "Nenhum arquivo Base64 informado"

    # Retorna o texto extraído do PDF em Base64
    def get_extracted_text(self) -> str:
        if self._extracted_text:
            # Codifica o texto extraído em Base64
            base64_text = base64.b64encode(
                self._extracted_text.encode('utf-8')).decode('utf-8')
            return base64_text
        return ""

    # Retorna possíveis erros
    def get_errors(self) -> str:
        return self._message_error
