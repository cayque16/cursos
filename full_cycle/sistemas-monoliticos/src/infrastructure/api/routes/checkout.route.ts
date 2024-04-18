import express, { Request, Response } from "express";
import CheckoutFacadeFactory from "../../../modules/checkout/factory/checkout.facade.factory";

export const checkoutRoute = express.Router();

checkoutRoute.post("/", async (req: Request, res: Response) => {
    const checkoutFacade = CheckoutFacadeFactory.create();

    const checkoutDto = {
        clientId: "1",
        products: [
            {productId: "1"},
            {productId: "2"},
            {productId: "3"},
        ]
    }

    const output = await checkoutFacade.execute(checkoutDto);
    res.send(output);
});