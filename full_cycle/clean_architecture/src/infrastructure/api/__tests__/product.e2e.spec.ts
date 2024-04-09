import {app, sequelize} from "../express";
import request from "supertest";

describe("E2E test for customer", () => {
    beforeEach(async () => {
        await sequelize.sync({ force: true});
    })

    afterAll(async () => {
        await sequelize.close();
    });

    it("should create a product", async () => {
        const response = await request(app)
            .post("/product")
            .send({
                name: "Fritadeira",
                price: 800
            });

        expect(response.status).toBe(200);
        expect(response.body.name).toBe("Fritadeira");
        expect(response.body.price).toBe(800);
    });

    it("should not create a product", async () => {
        const response = await request(app).post("/product").send({
            name: "Forno"
        });
        expect(response.status).toBe(500);
    });
});