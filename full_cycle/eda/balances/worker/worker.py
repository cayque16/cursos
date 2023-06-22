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

    while True:
        consumer = KafkaConsumer(
            'transactions',
            bootstrap_servers='192.168.2.10:9092',
            auto_offset_reset='earliest'
        )
        for msg in consumer:
            trasaction = json.loads(msg.value)

            amount = trasaction['Payload']['amount']

            sql_reduce_and_add = """UPDATE balance 
                SET balance = balance - {}
                WHERE account_id = "{}";

                UPDATE balance 
                SET balance = balance + {}
                WHERE account_id = "{}"
            """.format(
                amount, 
                trasaction['Payload']['account_id_from'],
                amount, 
                trasaction['Payload']['account_id_to']
            )