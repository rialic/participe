#!/bin/bash

openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout backend-key.pem \
    -out backend.pem \
    -subj "/C=BR/ST=MS/L=Campo Grande/O=Dev/CN=localhost" \
    -addext "subjectAltName=DNS:localhost,DNS:backend,DNS:*.localhost,IP:127.0.0.1"