const express = require('express')
const app  = express()
const port = 3000
const config = {
    host: 'db',
    user: 'root',
    password: 'root',
    database: 'nodedb'
};
const mysql = require('mysql')
const connection = mysql.createConnection(config)

const sql = `INSERT INTO people(name) values('Cayque')`
connection.query(sql)

app.get('/', (req,res) => {
    connection.query("SELECT * FROM people", function(err, result, fields) {
        retorno = '<h1>Full Cycle Rocks!</h1><ul>'
        result.forEach(element => {
            retorno = retorno + '<li>' + element.name + '</li>'
        });        
        retorno = retorno + '</ul>'
        res.send(retorno)
    });
})

app.listen(port, ()=> {
    console.log('Rodando na porta ' + port)
})