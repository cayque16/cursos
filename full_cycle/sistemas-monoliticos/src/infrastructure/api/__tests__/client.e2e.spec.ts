import request from "supertest";
import {app, sequelize} from "../express";

describe("E2E test for client", () => {
    beforeEach(async () => {
        await sequelize.sync({ force: true});
    })

    afterAll(async () => {
        await sequelize.close();
    });

    it("should create a client", async () => {
        const address = {
            street: "Rua tal",
            number: "55",
            complement: "Final da rua",
            city: "Aquela",
            state: "MG",
            zipCode: "1235-152"
        };
        const response = await request(app)
            .post("/clients")
            .send({
                name: "Fulano",
                email: "fulado@teste.com",
                document: "1234",
                address: address
            });

        expect(response.status).toBe(200);
    });
});