function div(a, b) {
    if (b == 0) {
        throw new Error('Divisão por zero não permitida!');
    }
    return a / b;
}

module.exports = div;