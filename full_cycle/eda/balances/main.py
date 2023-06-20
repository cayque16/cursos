import json
from kafka import KafkaConsumer
import mysql.connector as mysql
from bottle import Bottle, request, route, template

class Balances(Bottle):
    def __init__(self):
        super().__init__()

        self.route('/balances', method='POST', callback=self.get_balances)

    def get_balances(self):
        data = request.json
        return template("Recebemos o numero: {{num}}", num=data['account_id']) 
    
if __name__ == '__main__':
    balances = Balances()
    balances.run(host='0.0.0.0', port='3003', debug='True')