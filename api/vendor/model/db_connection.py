import mysql.connector
from mysql.connector import Error


class MySQLConnection:
    def __init__(self):
        self.connection = None  # Evita AttributeError
        try:
            self.connection = mysql.connector.connect(
                host='localhost',
                database='docverse',
                user='root',
                password='Sun147oi.'
            )
            if not self.connection.is_connected():
                raise ValueError("Não foi possível efetuar a conexão")
        except Error as e:
            print(f"Erro ao conectar no banco: {e}")
            raise  # Propaga o erro para o serviço saber que falhou

    def get_cursor(self):
        if self.connection and self.connection.is_connected():
            return self.connection.cursor()
        else:
            raise ConnectionError("Conexão não está ativa")

    def commit(self):
        if self.connection and self.connection.is_connected():
            self.connection.commit()

    def rollback(self):
        if self.connection and self.connection.is_connected():
            self.connection.rollback()

    def close(self):
        if self.connection and self.connection.is_connected():
            self.connection.close()
