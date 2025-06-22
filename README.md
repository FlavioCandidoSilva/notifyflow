# 🚀 NotifyFlow

Sistema de gerenciamento de notificações assíncronas usando **Laravel + RabbitMQ + MySQL + Docker + Xdebug**.

---

## 🛠️ Tecnologias utilizadas

- Laravel
- PHP 8.2
- RabbitMQ
- MySQL 8
- Docker + Docker Compose
- Xdebug (configurado)
- Queue (Workers + Jobs)

---

## 🔥 Funcionalidades

- ✅ API para criar e listar notificações
- ✅ Processamento assíncrono de notificações via RabbitMQ
- ✅ Status das notificações (`pending`, `processing`, `sent`)
- ✅ Workers utilizando filas com RabbitMQ
- ✅ Painel de gerenciamento RabbitMQ
- ✅ Banco de dados MySQL persistente
- ✅ Debug remoto com Xdebug (porta 9003)

---

## 📦 Instalação e execução

### 🚧 Pré-requisitos

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

### 🔥 Subindo o projeto

No terminal, dentro da pasta do projeto, execute:

```bash
docker-compose up --build -d
