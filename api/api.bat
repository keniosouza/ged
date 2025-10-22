@echo off
REM Caminho até o interpretador Python (ajuste se necessário)
set PYTHON_PATH=C:\Program Files\Python312\python.exe

REM Caminho até o seu script Python
set APP_PATH=D:\app\api\main.py

REM Iniciar a aplicação
echo Iniciando a aplicação Python...
start "" "%PYTHON_PATH%" "%APP_PATH%"

exit
