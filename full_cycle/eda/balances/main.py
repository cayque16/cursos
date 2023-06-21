import mysql.connector as mysql
from bottle import Bottle, request, template

class Balances(Bottle):
    def __init__(self):
        super().__init__()

        self.conn = mysql.connect(
            host="192.168.2.10",
            user="root",
            password="root",
            database="balances",
            port=3306
        )

        self.route('/balances', method='POST', callback=self.get_balances)

    def get_balances(self):
        data = request.json
        sql = """SELECT * 
            FROM balance 
            WHERE account_id = "%s"
        """ % (data['account_id'])

        cur = self.conn.cursor()
        cur.execute(sql)
        
        result = cur.fetchall()

        self.conn.commit()
        cur.close()

        if len(result) == 0:
            return "Conta não encontrada"
        else:
            return template("Saldo da conta {{account}}: {{balance}}", account=result[0][1], balance=result[0][2])
    
if __name__ == '__main__':
    balances = Balances()

    balances.run(host='0.0.0.0', port='3003', debug='True')