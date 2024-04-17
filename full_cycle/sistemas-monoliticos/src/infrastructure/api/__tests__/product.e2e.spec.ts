import {app, sequelize} from "../express";
import request from "supertest";

describe("E2E test for product", () => {
    beforeEach(async () => {
        await sequelize.sync({ force: true});
    })

    afterAll(async () => {
        await sequelize.close();
    });

    it("should create a product", async () => {
        const response = await request(app)
            .post("/products")
            .send({
                name: "Fritadeira",
                description: "Fritadeira 8L",
                purchasePrice: 800,
                stock: 15
            });

        expect(response.status).toBe(200);
        expect(response.body.id).toBeDefined();
        expect(response.body.name).toBe("Fritadeira");
        expect(response.body.description).toBe("Fritadeira 8L");
        expect(response.body.purchasePrice).toBe(800);
        expect(response.body.stock).toBe(15);
    });
});