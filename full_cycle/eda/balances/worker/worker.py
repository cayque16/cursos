import json
import mysql.connector as mysql
from kafka import KafkaConsumer

if __name__ == '__main__':
    conn = mysql.connect(
        host="192.168.2.10",
        user="root",
        password="root",
        database="balances",
        port=3306
    )

    print("Worker online, esperando transacoes...")

    while True:
        consumer = KafkaConsumer(
            'transactions',
            bootstrap_servers='192.168.2.10:9092'
        )
        for msg in consumer:
            trasaction = json.loads(msg.value)

            amount = trasaction['Payload']['amount']

            sql_reduce = """UPDATE balance 
                SET balance = balance - {}
                WHERE account_id = "{}"
            """.format(
                amount, 
                trasaction['Payload']['account_id_from'],
            )
            
            cur = conn.cursor()
            cur.execute(sql_reduce)
            conn.commit()
            cur.close()
            print("Saldo da conta {} atualizado...".format(trasaction['Payload']['account_id_from']))

            sql_add = """UPDATE balance 
                SET balance = balance + {}
                WHERE account_id = "{}"
            """.format(
                amount, 
                trasaction['Payload']['account_id_to'],
            )

            cur = conn.cursor()
            cur.execute(sql_add)
            conn.commit()
            cur.close()
            print("Saldo da conta {} atualizado...".format(trasaction['Payload']['account_id_to']))