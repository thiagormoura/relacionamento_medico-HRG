import imaplib
import email
from email.header import decode_header
from datetime import datetime
import email.utils
import mysql.connector

# Conectar ao servidor IMAP
server = 'imap.gmail.com'
email_user = 'vitoriapinheiro775@gmail.com'
email_pass = 'zdzx jfcb dazv nyuw'

# Conectar e autenticar
mail = imaplib.IMAP4_SSL(server)
mail.login(email_user, email_pass)

# Selecionar a caixa de entrada
mail.select('inbox')

# Pesquisar e-mails com "HRG:" no assunto
buscar_termos = ['Atendimento', 'Pedido', 'Cadastro']
email_ids = set()

for termo in buscar_termos:
    status, response = mail.search(None, f'SUBJECT "{termo} HRG:"')
    email_ids.update(response[0].split())

# Conectar ao banco de dados MySQL
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="relacionamentomedico"
)

cursor = db.cursor()

# Recuperar e-mails e armazenar em uma lista com data de recebimento
emails_data = []

for email_id in email_ids:
    status, data = mail.fetch(email_id, '(RFC822)')
    msg = email.message_from_bytes(data[0][1])
    
    # Decodificar o assunto
    subject, encoding = decode_header(msg.get('Subject'))[0]
    if isinstance(subject, bytes):
        try:
            subject = subject.decode(encoding if encoding else 'utf-8')
        except (LookupError, UnicodeDecodeError):
            subject = subject.decode('utf-8', errors='replace')
    
    # Verificar se o assunto contém "HRG:"
    if "HRG:" in subject:
        # Extrair a parte do assunto após "HRG:"
        novo_assunto = subject.split("HRG:", 1)[-1].strip()

        # Obter a data de recebimento
        data_recebimento = msg.get('Date')
        data_recebimento = email.utils.parsedate_to_datetime(data_recebimento)
        
        if data_recebimento.tzinfo is not None:
            data_recebimento = data_recebimento.replace(tzinfo=None)
        
        # Extrair o corpo do e-mail
        body = ""
        if msg.is_multipart():
            for part in msg.walk():
                if part.get_content_type() == 'text/plain':
                    body += part.get_payload(decode=True).decode('utf-8', errors='replace')
        else:
            body = msg.get_payload(decode=True).decode('utf-8', errors='replace')

        # Obter o e-mail do remetente
        senderemail = msg.get('From')
        _, sender_email = email.utils.parseaddr(senderemail)
        
        # Obter o remetente
        sender = msg.get('From')
        sender_name, _ = email.utils.parseaddr(sender)
        if sender_name:
            sender_name = email.header.decode_header(sender_name)[0][0]
            if isinstance(sender_name, bytes):
                sender_name = sender_name.decode('utf-8')
        else:
            sender_name = "Desconhecido"
        
        veiculoatendimento = "E-mail"

        # Verificar se o profissional já está na tabela
        cursor.execute("SELECT id FROM profissionais WHERE email = %s", (sender_email,))
        result = cursor.fetchone()
        if result:
            profissional_id = result[0]
        else:
            # Inserir o profissional e obter o ID gerado
            cursor.execute("INSERT INTO profissionais (nome, email) VALUES (%s, %s)", (sender_name, sender_email))
            profissional_id = cursor.lastrowid

        # Adicionar os dados à lista
        emails_data.append((data_recebimento, profissional_id, veiculoatendimento, novo_assunto, body))

# Ordenar e-mails por data de recebimento
emails_data.sort(key=lambda x: x[0])

# Inserir os dados na tabela atendimento
for data_recebimento, profissional_id, veiculoatendimento, novo_assunto, descricao in emails_data:
    query2 = """
    INSERT INTO atendimento (data, veiculo_atendimento, assunto, descricao, situacao, profissional)
    VALUES (%s, %s, %s, %s, %s, %s)
    """
    values2 = (data_recebimento, veiculoatendimento, novo_assunto, descricao, "Aberto", profissional_id)
    cursor.execute(query2, values2)

# Confirmar as alterações
db.commit()

# Fechar a conexão com o banco de dados e o servidor IMAP
cursor.close()
db.close()
mail.logout()