import uvicorn
from fastapi import FastAPI
from pydantic import BaseModel
from pydantic import BaseModel, EmailStr, constr
from vendor.model.encryption import Encryption
from vendor.controller.auth.authValidate import AuthValidate
from vendor.model.auth import Auth
from vendor.controller.image.imageValidate import ImageValidate
from vendor.action.image.image_to_word import ImageToWord
from vendor.action.image.image_to_text import ImageToText
from vendor.controller.pdf.pdfValidate import PdfValidate
from vendor.action.pdf.pdf_to_word import PdfToWord
from vendor.action.pdf.pdf_to_text import PdfToText

app = FastAPI(title="DocVerso - Gerenciamento Eletrônico de Documentos")

# Token
# Defina um modelo de dados usando Pydantic para o corpo da requisição


class AuthRequest(BaseModel):

    # Valida o formato do e-mail
    auth_email: EmailStr

    # Define o tamanho da senha
    auth_password: constr(min_length=12, max_length=30)


@app.post("/token")
async def token(post: AuthRequest):

    try:

        # Instância a classe e passa os parametros a serem tratados e sanitizados
        authValidate = AuthValidate(post.auth_email, post.auth_password)

        # Faz a validação dos parametros informados
        if authValidate.valid_auth():

            # Cria a instância da classe com os dados do usário
            auth = Auth(authValidate.get_auth_email(),
                        authValidate.get_auth_password())

            # Feita a validação, efetua a consulta do usuário junto ao banco de dados
            auth._search()

            # Verifica erros ocorreram
            if auth.get_errors():
                return {"status": "error", "message": auth.get_errors()}

            # Se não houver erros, retorna a chave para gerar o token
            return {"status": "success", "token": auth.get_new_key()}

        # Caso existam erros, informo
        else:
            return {"status": "error", "message": authValidate.get_errors()}

    except Exception as e:
        return {"status": "error", "message": str(e)}


# Criptografa
class EncryptRequest(BaseModel):
    # Verifica o tamanho do password enviado
    password: constr(min_length=12, max_length=30)


@app.post("/encrypt")
async def encrypt(post: EncryptRequest):

    # Instância da classe, passando como paramtro uma string a ser criptografada
    encrypt = Encryption(post.password)

    # Efetua a criptografia do texto enviado
    encrypt.encrypt()

    # Verifica erros ocorreram
    if encrypt.get_errors():
        return {"status": "error", "message": encrypt.get_errors()}

    else:
        # Se não houver erros, retorna o texto criptografado
        return {"status": "success", "encrypted_text": encrypt._return_encrypted_or_decripted_text()}


# Descriptografia


@app.post("/decrypt")
async def decrypt(post: EncryptRequest):

    # Instância da classe, passando como paramtro uma string a ser criptografada
    decrypt = Encryption(post.password)

    # Efetua a criptografia do texto enviado
    decrypt.decrypt()

    # Verifica erros ocorreram
    if decrypt.get_errors():
        return {"status": "error", "message": decrypt.get_errors()}

    else:
        # Se não houver erros, retorna o texto criptografado
        return {"status": "success", "decrypted_text": decrypt._return_encrypted_or_decripted_text()}


# Converte imagem em texto e para documento word

class ImageRequest(BaseModel):

    # Base64 da iamgem a ser convertida
    base64_string: str

    # token de acesso ao serviço
    token: str


@app.post("/image_to_word")
async def image_to_word(post: ImageRequest):

    # Instância da classe para validação do token
    auth = Auth(None, None)

    # Consulta o token
    if auth.check_token(post.token):

        # Instância a classe e passa os parametros a serem tratados e sanitizados
        image_validate = ImageValidate(post.base64_string)

        # Verifica se o arquivo base64 foi informado
        base64_string = image_validate.is_base64()

        # Verifica se erros ocorreram
        if image_validate.get_errors():
            return {"status": "error", "message": image_validate.get_errors()}

        # Instância da classe, passando como parametro o buffer da imagem
        image_to_word = ImageToWord(base64_string)

        # Se não houver erros, retorna o texto criptografado
        return {"status": "success", "base64_file": image_to_word.get_base64()}

    else:
        # Se houver erros, informo
        return {"status": "error", "message": auth.get_errors()}


# Converte imagem em texto
@app.post("/image_to_text")
async def image_to_text(post: ImageRequest):

    # Instância da classe para validação do token
    auth = Auth(None, None)

    # Consulta o token
    if auth.check_token(post.token):

        # Instância a classe e passa os parametros a serem tratados e sanitizados
        image_validate = ImageValidate(post.base64_string)

        # Verifica se o arquivo base64 foi informado
        base64_string = image_validate.is_base64()

        # Verifica se erros ocorreram
        if image_validate.get_errors():
            return {"status": "error", "message": image_validate.get_errors()}

        # Instância da classe, passando como parametro o buffer da imagem
        image_to_text = ImageToText(base64_string)

        # Se não houver erros, retorna o texto criptografado
        return {"status": "success", "base64_file": image_to_text.get_base64()}

    else:
        # Se houver erros, informo
        return {"status": "error", "message": auth.get_errors()}


# Converte arquivo PDF para Word
class PdfRequest(BaseModel):

    # Base64 da iamgem a ser convertida
    base64_string: str

    # token de acesso ao serviço
    token: str


@app.post("/pdf_to_word")
async def pdf_to_word(post: PdfRequest):

    # Instância da classe para validação do token
    auth = Auth(None, None)

    # Consulta o token
    if auth.check_token(post.token):

        # Instância a classe e passa os parametros a serem tratados e sanitizados
        pdf_validate = PdfValidate(post.base64_string)

        # Verifica se o arquivo base64 foi informado
        base64_string = pdf_validate.is_base64()

        # Verifica se erros ocorreram
        if pdf_validate.get_errors():
            return {"status": "error", "message": pdf_validate.get_errors()}

        # Instância da classe, passando como parametro o buffer da imagem
        pdf_to_word = PdfToWord(base64_string)

        # Se não houver erros, retorna o texto criptografado
        return {"status": "success", "base64_file": pdf_to_word.get_base64()}

    else:
        # Se houver erros, informo
        return {"status": "error", "message": auth.get_errors()}


# Converte arquivo PDF para txt
@app.post("/pdf_to_text")
async def pdf_to_text(post: PdfRequest):

    # Instância da classe para validação do token
    auth = Auth(None, None)

    # Consulta o token
    if auth.check_token(post.token):

        # Instância a classe e passa os parametros a serem tratados e sanitizados
        pdf_validate = PdfValidate(post.base64_string)

        # Verifica se o arquivo base64 foi informado
        base64_string = pdf_validate.is_base64()

        # Verifica se erros ocorreram
        if pdf_validate.get_errors():
            return {"status": "error", "message": pdf_validate.get_errors()}

        # Instância da classe, passando como parametro o buffer da imagem
        pdf_to_text = PdfToText(base64_string)

        # Se não houver erros, retorna o texto criptografado
        return {"status": "success", "base64_file": pdf_to_text.get_extracted_text()}

    else:
        # Se houver erros, informo
        return {"status": "error", "message": auth.get_errors()}

    # Inicializa o servidor
if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)
