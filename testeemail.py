import imaplib
import email
from email.header import decode_header

# Conectar ao servidor de e-mail
mail = imaplib.IMAP4_SSL('imap.gmail.com')
mail.login('vitoriapinheiro775@gmail.com', 'zdzx jfcb dazv nyuw')  # Substitua pelas suas credenciais
mail.select('inbox')

# Buscar e-mails não lidos
status, data = mail.search(None, 'UNSEEN')
email_ids = data[0].split()

# Limitar o número de e-mails a processar
limit = 2
email_ids = email_ids[-limit:]  # Pegando os últimos 10 e-mails

for e_id in email_ids:
    # Buscar o e-mail pelo ID
    status, email_data = mail.fetch(e_id, '(RFC822)')
    
    # Processar o e-mail
    email_msg = email.message_from_bytes(email_data[0][1])
    
    # Decodificar o assunto do e-mail
    subject, encoding = decode_header(email_msg['Subject'])[0]
    if isinstance(subject, bytes):
        try:
            subject = subject.decode(encoding if encoding else 'utf-8')
        except (LookupError, TypeError):
            # Se a codificação não for reconhecida, usar 'utf-8' como fallback
            subject = subject.decode('utf-8', errors='ignore')
    
    # Decodificar o remetente
    sender = email_msg.get('From')
    
    # Obter a data do e-mail
    date_received = email_msg.get('Date')
    
    # Processar o corpo do e-mail
    body = None
    if email_msg.is_multipart():
        for part in email_msg.walk():
            content_type = part.get_content_type()
            content_disposition = str(part.get('Content-Disposition'))
            if 'attachment' not in content_disposition:
                if part.get_payload(decode=True) is not None:
                    body = part.get_payload(decode=True).decode(errors='ignore')
                break
    else:
        if email_msg.get_payload(decode=True) is not None:
            body = email_msg.get_payload(decode=True).decode(errors='ignore')
    
    # Imprimir informações do e-mail
    print(f'Subject: {subject}')
    print(f'From: {sender}')
    print(f'Date: {date_received}')
    print(f'Body: {body}')
    print('-')




# Fechar a conexão
mail.logout()