import request from "supertest";
import { app, sequelize } from "../express";

describe("E2E test for checkout", () => {
    beforeEach(async () => {
        await sequelize.sync({ force: true});
    })

    afterAll(async () => {
        await sequelize.close();
    });

    it("should create a checkout", async () => {
        // https://medium.com/@major_piotr/factory-pattern-in-jest-37be1e5eb686
        // const address = {
        //     street: "Rua tal",
        //     number: "55",
        //     complement: "Final da rua",
        //     city: "Aquela",
        //     state: "MG",
        //     zipCode: "1235-152"
        // };
        // await request(app)
        //     .post("/clients")
        //     .send({
        //         id: "1",
        //         name: "Fulano",
        //         email: "fulado@teste.com",
        //         document: "1234",
        //         address: address
        //     });
        const checkoutDto = {
            clientId: "1",
            products: [
                {productId: "1"},
                {productId: "2"},
                {productId: "3"},
            ]
        }

        const response = await request(app)
            .post("/checkout")
            .send(checkoutDto);

        expect(response.status).toBe(200);
    });
});