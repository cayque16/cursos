const soma = require('./soma');
const sub = require('./sub');
const mult = require('./mult');
const div = require('./div');

describe('Testes para calculadora', () => {
    num1 = getRandomInt(0, 500);
    num2 = getRandomInt(0, 500);

    resultSoma = num1 + num2;
    it('A soma deve retornar ' + resultSoma + ' quando os numeros forem ' + num1 + ' e ' + num2, () => {
        expect(soma(num1, num2)).toEqual(resultSoma);
    });

    resultSub = num1 - num2;
    it('A subtração deve retornar ' + resultSub + ' quando os numeros forem ' + num1 + ' e ' + num2, () => {
        expect(sub(num1, num2)).toEqual(resultSub);
    });

    resultMult = num1 * num2;
    it('A multiplicação deve retornar ' + resultMult + ' quando os numeros forem ' + num1 + ' e ' + num2, () => {
        expect(mult(num1, num2)).toEqual(resultMult);
    });

    resultDiv = num1 / num2;
    it('A divisão deve retornar ' + resultDiv + ' quando os numeros forem ' + num1 + ' e ' + num2, () => {
        expect(div(num1, num2)).toEqual(resultDiv);
    });

    it('A divisão por zero deve retornar uma exceção', () => {
        expect(() => div(num1, 0)).toThrow();
    });
});

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min) + min)
}