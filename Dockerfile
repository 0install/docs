FROM python:3.8-slim
COPY requirements.txt .
RUN pip install -r requirements.txt
WORKDIR /app
ENV LC_ALL=C.UTF-8 LANG=C.UTF-8
ENTRYPOINT ["mkdocs"]
