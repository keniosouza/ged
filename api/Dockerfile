FROM python:3.12-slim

ENV DEBIAN_FRONTEND=noninteractive

# Instala dependências do sistema, incluindo Tesseract, MariaDB e utilitários para mysqlclient
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    pkg-config \
    tesseract-ocr \
    tesseract-ocr-por \
    libglib2.0-0 \
    libsm6 \
    libxext6 \
    libxrender-dev \
    libgl1 \
    libmariadb-dev-compat \
    libmariadb-dev \
    libxml2-dev \
    libxslt1-dev \
    libffi-dev \
    libjpeg-dev \
    zlib1g-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . .

RUN pip install --upgrade pip \
    && pip install --no-cache-dir -r requirements.txt

EXPOSE 8082

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8082"]
